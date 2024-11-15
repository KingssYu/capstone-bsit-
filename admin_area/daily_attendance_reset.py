import mysql.connector
from datetime import datetime, timedelta
import logging

logging.basicConfig(filename='attendance_reset.log', level=logging.INFO, 
                    format='%(asctime)s - %(levelname)s - %(message)s')

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

def move_attendance_to_reports():
    try:
        conn = get_db_connection()
        cursor = conn.cursor()

        yesterday = (datetime.now() - timedelta(days=1)).strftime('%Y-%m-%d')

        # Move yesterday's attendance data to reports table
        cursor.execute("""
            INSERT INTO attendance_reports (employee_no, first_name, last_name, date, clock_in, clock_out, status)
            SELECT a.employee_no, e.first_name, e.last_name, a.date, a.clock_in, a.clock_out, a.status
            FROM attendance a
            JOIN adding_employee e ON a.employee_no = e.employee_no
            WHERE DATE(a.date) = %s
        """, (yesterday,))

        # Delete yesterday's data from the attendance table
        cursor.execute("DELETE FROM attendance WHERE DATE(date) = %s", (yesterday,))

        conn.commit()
        logging.info(f"Successfully moved {cursor.rowcount} records to reports and cleared attendance table.")

        cursor.close()
        conn.close()

    except mysql.connector.Error as err:
        logging.error(f"Database error: {err}")
        raise

if __name__ == "__main__":
    try:
        move_attendance_to_reports()
        logging.info("Daily attendance reset completed successfully.")
    except Exception as e:
        logging.error(f"Error during daily attendance reset: {str(e)}")