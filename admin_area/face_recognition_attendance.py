import cv2
import numpy as np
import face_recognition
import os
import json
import mysql.connector
from datetime import datetime, timedelta, time
import logging
import sys
import traceback
import schedule
import time as time_module

logging.basicConfig(filename='face_recognition_debug.log', level=logging.DEBUG, 
                    format='%(asctime)s - %(levelname)s - %(message)s')

def find_camera(camera_type):
    logging.info(f"Searching for {camera_type} camera")
    
    if camera_type == "usb":
        camera_indices = range(1, 10)  # USB cameras usually start from index 1
    else:
        camera_indices = [0]  # Web camera is usually at index 0
    
    for i in camera_indices:
        try:
            logging.info(f"Attempting to open camera at index {i}")
            cap = cv2.VideoCapture(i)
            if cap.isOpened():
                ret, frame = cap.read()
                if ret and frame is not None and frame.size > 0:
                    logging.info(f"Found camera at index {i}")
                    return cap, i
                cap.release()
            else:
                logging.info(f"Failed to open camera at index {i}")
        except Exception as e:
            logging.error(f"Error trying camera index {i}: {str(e)}")
    
    logging.error(f"No {camera_type} camera found")
    raise Exception(f"No {camera_type} camera found")

def get_db_connection():
    try:
        return mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="admin_login"
        )
    except mysql.connector.Error as err:
        logging.error(f"Database connection error: {err}")
        raise

def log_attendance(employee_no, attendance_type):
    try:
        conn = get_db_connection()
        cursor = conn.cursor()

        current_time = datetime.now()
        current_date = current_time.date()

        # Define time thresholds
        start_time = datetime.combine(current_date, time(8, 0))  # 8:00 AM
        end_time = datetime.combine(current_date, time(17, 0))  # 5:00 PM
        lunch_start = datetime.combine(current_date, time(12, 0))  # 12:00 PM
        lunch_end = datetime.combine(current_date, time(13, 0))  # 1:00 PM

        cursor.execute("SELECT * FROM attendance WHERE employee_no = %s AND DATE(date) = CURDATE()", (employee_no,))
        existing_record = cursor.fetchone()

        if attendance_type == 'in':
            if existing_record is None:
                if current_time.time() < time(5, 0):  # Before 5:00 AM
                    message = "Attendance not allowed before 5:00 AM"
                else:
                    status = 'Present' if current_time <= start_time else 'Late'
                    cursor.execute("INSERT INTO attendance (employee_no, date, clock_in, status) VALUES (%s, %s, %s, %s)",
                                   (employee_no, current_date, start_time, status))
                    
                    cursor.execute("""
                        INSERT INTO attendance_report (employee_no, employee_name, status, date, time_in)
                        VALUES (%s, (SELECT CONCAT(first_name, ' ', last_name) FROM adding_employee WHERE employee_no = %s), %s, %s, %s)
                        ON DUPLICATE KEY UPDATE
                            status = VALUES(status),
                            time_in = VALUES(time_in)
                    """, (employee_no, employee_no, status, current_date, start_time.time()))
                    
                    message = f"Clock in successful. Status: {status}"
            else:
                message = "Already clocked in"
        else:  # attendance_type == 'out'
            if existing_record is None:
                message = "No clock-in record found"
            else:
                clock_in_time = existing_record[3]  # Assuming clock_in is the 4th column
                worked_time = calculate_worked_time(clock_in_time, current_time)
                overtime = calculate_overtime(worked_time)
                
                cursor.execute("UPDATE attendance SET clock_out = %s, worked_time = %s, overtime = %s WHERE employee_no = %s AND DATE(date) = CURDATE()",
                               (current_time, worked_time, overtime, employee_no))
                
                cursor.execute("""
                    UPDATE attendance_report 
                    SET time_out = %s, actual_time = %s, overtime = %s
                    WHERE employee_no = %s AND DATE(date) = CURDATE()
                """, (current_time.time(), worked_time, overtime, employee_no))
                
                message = f"Clock out successful. Worked time: {worked_time}, Overtime: {overtime}"

        conn.commit()
        cursor.close()
        conn.close()

        return message
    except mysql.connector.Error as err:
        logging.error(f"Database error: {err}")
        raise

def calculate_worked_time(clock_in, clock_out):
    start_time = max(clock_in, datetime.combine(clock_in.date(), time(8, 0)))
    end_time = min(clock_out, datetime.combine(clock_out.date(), time(17, 0)))
    lunch_start = datetime.combine(clock_in.date(), time(12, 0))
    lunch_end = datetime.combine(clock_in.date(), time(13, 0))

    total_time = end_time - start_time
    lunch_duration = min(timedelta(hours=1), max(timedelta(0), min(end_time, lunch_end) - max(start_time, lunch_start)))
    worked_time = total_time - lunch_duration

    return worked_time

def calculate_overtime(worked_time):
    regular_hours = timedelta(hours=8)
    if worked_time <= regular_hours:
        return timedelta(0)
    
    overtime = worked_time - regular_hours
    overtime_hours = overtime.total_seconds() / 3600
    rounded_overtime = round(overtime_hours * 2) / 2  # Round to nearest 0.5 hour
    return timedelta(hours=rounded_overtime)



def write_json_result(data):
    try:
        result_file = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'attendance_result.json')
        with open(result_file, 'w') as f:
            json.dump(data, f)
        logging.info(f"Successfully wrote result to JSON file: {result_file}")
    except Exception as e:
        logging.error(f"Error writing to JSON file: {str(e)}")
        logging.info(f"Attendance result: {json.dumps(data)}")

def load_employee_faces(base_path):
    known_face_encodings = []
    known_face_names = []
    employee_faces_path = os.path.join(base_path, 'employee_faces')

    for employee_no in os.listdir(employee_faces_path):
        employee_folder = os.path.join(employee_faces_path, employee_no)
        if os.path.isdir(employee_folder):
            employee_encodings = []
            for face_image in os.listdir(employee_folder):
                image_path = os.path.join(employee_folder, face_image)
                face_image = face_recognition.load_image_file(image_path)
                face_encoding = face_recognition.face_encodings(face_image)
                
                if len(face_encoding) > 0:
                    employee_encodings.append(face_encoding[0])
                else:
                    logging.warning(f"No face found in {image_path}")
            
            if employee_encodings:
                known_face_encodings.append(np.mean(employee_encodings, axis=0))
                known_face_names.append(employee_no)
            else:
                logging.warning(f"No valid face encodings found for employee {employee_no}")

    logging.info(f"Loaded {len(known_face_encodings)} employee faces")
    return known_face_encodings, known_face_names

def get_employee_name(employee_no):
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT first_name, last_name FROM adding_employee WHERE employee_no = %s", (employee_no,))
    employee_data = cursor.fetchone()
    cursor.close()
    conn.close()
    
    if employee_data:
        return f"{employee_data[0]} {employee_data[1]}"
    else:
        return "Unknown"

def face_recognition_attendance(camera_type, attendance_type):
    try:
        logging.info(f"Starting face recognition attendance with {camera_type} camera for {attendance_type}")
        
        base_path = os.path.dirname(os.path.abspath(__file__))
        logging.info(f"Base path: {base_path}")
        
        known_face_encodings, known_face_names = load_employee_faces(base_path)

        if not known_face_encodings:
            logging.warning("No employee faces loaded. Exiting.")
            write_json_result({"error": "No employee faces loaded"})
            return

        logging.info(f"Attempting to find {camera_type} camera")
        cap, camera_index = find_camera(camera_type)
        logging.info(f"Using camera at index {camera_index}")

        cv2.namedWindow("Attendance System", cv2.WINDOW_NORMAL)
        cv2.resizeWindow("Attendance System", 640, 480)

        recognized_employees = set()
        last_recognition_time = {}

        while True:
            ret, frame = cap.read()
            if not ret or frame is None:
                logging.error("Failed to capture image from camera")
                continue

            current_time = datetime.now().time()
            if time(0, 1) <= current_time < time(5, 0):
                cv2.putText(frame, "Attendance not allowed (12:01 AM - 4:59 AM)", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
                cv2.imshow("Attendance System", frame)
                if cv2.waitKey(1) & 0xFF == ord('q'):
                    break
                continue

            rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            face_locations = face_recognition.face_locations(rgb_frame, model="hog")
            face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

            for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
                face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
                best_match_index = np.argmin(face_distances)
                
                if face_distances[best_match_index] < 0.6:  # Adjust this threshold as needed
                    employee_no = known_face_names[best_match_index]
                    current_time = datetime.now()
                    
                    # Check if the employee was recognized in the last 5 minutes
                    if employee_no not in last_recognition_time or (current_time - last_recognition_time[employee_no]) > timedelta(minutes=5):
                        logging.info(f"Face recognized: {employee_no}")
                        message = log_attendance(employee_no, attendance_type)
                        
                        employee_name = get_employee_name(employee_no)
                        
                        result = {
                            "employee_no": employee_no,
                            "employee_name": employee_name,
                            "message": message
                        }
                        write_json_result(result)
                        recognized_employees.add(employee_no)
                        last_recognition_time[employee_no] = current_time
                    
                    # Draw a green rectangle for recognized face
                    cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
                    cv2.putText(frame, f"{employee_name} ({employee_no})", (left + 6, bottom - 6), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                else:
                    # Draw a red rectangle for unrecognized face
                    cv2.rectangle(frame, (left, top), (right, bottom), (0, 0, 255), 2)
                    cv2.putText(frame, "Unknown", (left + 6, bottom - 6), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)

            # Display the number of recognized employees
            cv2.putText(frame, f"Recognized: {len(recognized_employees)}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

            # Display current time
            current_time_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            cv2.putText(frame, f"Time: {current_time_str}", (10, 60), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 255, 255), 2)

            cv2.imshow("Attendance System", frame)

            if cv2.waitKey(1) & 0xFF == ord('q'):
                break

        cap.release()
        cv2.destroyAllWindows()

    except Exception as e:
        logging.error(f"Error in face_recognition_attendance: {str(e)}")
        logging.error(f"Stack trace: {traceback.format_exc()}")
        write_json_result({"error": str(e)})

def mark_absent_employees():
    try:
        conn = get_db_connection()
        cursor = conn.cursor()

        current_date = datetime.now().date()

        # Mark absent employees in the attendance table and attendance_report table
        cursor.execute("""
            INSERT INTO attendance (employee_no, date, status)
            SELECT e.employee_no, %s, 'Absent'
            FROM adding_employee e
            LEFT JOIN attendance a ON e.employee_no = a.employee_no AND DATE(a.date) = %s
            WHERE a.employee_no IS NULL
        """, (current_date, current_date))

        cursor.execute("""
            INSERT INTO attendance_report (employee_no, employee_name, status, date)
            SELECT e.employee_no, CONCAT(e.first_name, ' ', e.last_name), 'Absent', %s
            FROM adding_employee e
            LEFT JOIN attendance_report ar ON e.employee_no = ar.employee_no AND DATE(ar.date) = %s
            WHERE ar.employee_no IS NULL
        """, (current_date, current_date))

        conn.commit()
        cursor.close()
        conn.close()

        logging.info(f"Marked absent employees for {current_date}")
    except mysql.connector.Error as err:
        logging.error(f"Database error while marking absent employees: {err}")

def transfer_attendance_to_report():
    try:
        conn = get_db_connection()
        cursor = conn.cursor()

        # Get current date (which is the date that just ended)
        current_date = datetime.now().date()

        # Update attendance_report table with any missing records or changes
        cursor.execute("""
            INSERT INTO attendance_report (employee_no, employee_name, status, date, time_in, time_out, actual_time)
            SELECT 
                a.employee_no, 
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                a.status,
                a.date,
                TIME(a.clock_in),
                TIME(a.clock_out),
                TIMEDIFF(IFNULL(a.clock_out, NOW()), a.clock_in) as actual_time
            FROM attendance a
            JOIN adding_employee e ON a.employee_no = e.employee_no
            WHERE DATE(a.date) = %s
            ON DUPLICATE KEY UPDATE
                status = VALUES(status),
                time_in = VALUES(time_in),
                time_out = VALUES(time_out),
                actual_time = VALUES(actual_time)
        """, (current_date,))

        # Clear the attendance table for the new day
        cursor.execute("DELETE FROM attendance WHERE DATE(date) = %s", (current_date,))

        conn.commit()
        cursor.close()
        conn.close()

        logging.info(f"Successfully transferred attendance records for {current_date} to attendance_report and cleared attendance table")
    except mysql.connector.Error as err:
        logging.error(f"Database error during transfer: {err}")

def schedule_daily_tasks():
    schedule.every().day.at("17:00").do(mark_absent_employees)
    schedule.every().day.at("23:59").do(transfer_attendance_to_report)

if __name__ == "__main__":
    schedule_daily_tasks()

    if len(sys.argv) < 3:
        print(json.dumps({"error": "Usage: python face_recognition_attendance.py <camera_type> <attendance_type>"}))
        sys.exit(1)
    
    camera_type = sys.argv[1]
    attendance_type = sys.argv[2]

    face_recognition_attendance(camera_type, attendance_type)

    while True:
        schedule.run_pending()
        time_module.sleep(1)