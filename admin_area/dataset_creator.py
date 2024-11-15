import cv2
import numpy as np
import mysql.connector
import os

# Initialize the face detector
face_cascade_path = 'admin_area/cascades/haarcascade_frontalface_default.xml'
faceDetect = cv2.CascadeClassifier(face_cascade_path)

if faceDetect.empty():
    raise Exception(f"Failed to load cascade classifier from {face_cascade_path}")

# Start the camera
cam = cv2.VideoCapture(0)
if not cam.isOpened():
    raise Exception("Error: Could not open video device")

# Create a connection to the MySQL database
try:
    db = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="admin_login"
    )
    cursor = db.cursor()
except mysql.connector.Error as err:
    print(f"Error: {err}")
    exit()

# Ask for employee details
employee_no = input("Enter Employee ID: ")
last_name = input("Enter Last Name: ")
first_name = input("Enter First Name: ")
middle_name = input("Enter Middle Name: ")
email = input("Enter Email: ")
position = input("Enter Position: ")
contact_no = input("Enter Contact No.: ")
department = input("Enter Department: ")
date_hired = input("Enter Date Hired (YYYY-MM-DD): ")
address = input("Enter Address: ")

# Check if the employee already exists in the database
cursor.execute("SELECT * FROM adding_employee WHERE employee_no = %s", (employee_no,))
result = cursor.fetchone()

if result:
    print(f"Employee with ID {employee_no} already exists in the database.")
else:
    # Create a dataset directory inside admin_area if it doesn't exist
    dataset_dir = "admin_area/employee_folder"
    if not os.path.exists(dataset_dir):
        os.makedirs(dataset_dir)

    # Create a folder for this employee's face data
    employee_folder = os.path.join(dataset_dir, employee_no)
    if not os.path.exists(employee_folder):
        os.makedirs(employee_folder)

    # Capture 100 face samples
    sample_num = 0

    while True:
        # Capture frame-by-frame
        ret, img = cam.read()
        if not ret:
            print("Failed to grab frame")
            break

        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

        # Detect faces
        faces = faceDetect.detectMultiScale(gray, 1.3, 5)

        # For each detected face
        for (x, y, w, h) in faces:
            sample_num += 1
            # Save the captured face image in the dataset folder
            face_img = gray[y:y+h, x:x+w]
            cv2.imwrite(f"{employee_folder}/User.{employee_no}.{sample_num}.jpg", face_img)

            # Draw rectangle around the face (just for visual confirmation)
            cv2.rectangle(img, (x, y), (x+w, y+h), (255, 0, 0), 2)
            cv2.putText(img, f"Samples Collected: {sample_num}", (x, y-10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (255, 0, 0), 2)

            print(f"Sample {sample_num} collected and saved.")

        # Display the video feed in a popup window
        cv2.imshow('Camera Feed', img)

        # Break if we have collected 100 face samples or 'q' is pressed
        if sample_num >= 100:
            print("Collected 100 samples. Stopping...")
            break
        
        # If 'q' is pressed, exit the loop
        if cv2.waitKey(1) & 0xFF == ord('q'):
            print("Quit pressed. Stopping...")
            break

    # Insert employee record into the new table with all the input details
    try:
        sql = """
        INSERT INTO adding_employee (employee_no, last_name, first_name, middle_name, email, position, contact, department, date_hired, address, face_samples)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        values = (employee_no, last_name, first_name, middle_name, email, position, contact_no, department, date_hired, address, sample_num)
        cursor.execute(sql, values)
        db.commit()
        print(f"Employee {employee_no} added to the database with {sample_num} face samples.")
    except mysql.connector.Error as err:
        print(f"Error inserting into the database: {err}")

# Release the camera and close the windows
cam.release()
cv2.destroyAllWindows()

# Close the database connection
cursor.close()
db.close()
