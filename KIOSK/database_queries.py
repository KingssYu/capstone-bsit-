import mysql.connector
from datetime import datetime, timedelta, time
import os
import random as rd



class Database_queries:
    def __init__(self):
        config_file = "config.txt"
        if os.path.exists(config_file):
            with open(config_file) as file:
                __conf = [x.replace("\n", "") for x in file.readlines()]
        else:
            open(config_file, "w")

        self.__host = __conf[0]
        self.__user = __conf[1]
        self.__database = __conf[2]
        self.__password = __conf[3]
        self.__port = int(__conf[4])


    def __create_connection(self):
        '''start a connection to the sql database'''
        connect_to_database = mysql.connector.connect(
        host = self.__host,
        user = self.__user,
        database= self.__database,
        password = self.__password,
        port = self.__port
        )

        mycursor = connect_to_database.cursor()
        return connect_to_database, mycursor
    

    def __closeConnection(self, dbCursor, database):
        '''to end or close the connection of cursor and database'''
        dbCursor.close()
        database.close()


    def __get_qry(self, cols, tbl, cond=""):
        '''
        This query must retrieve data in the database:
        '''
        try:
            db, cursor = self.__create_connection()

            qry = f'''SELECT {cols} FROM {tbl}'''
            
            if cond != "":
                qry += f" WHERE {cond}"

            cursor.execute(qry)
            result = cursor.fetchall()

            self.__closeConnection(cursor, db)

            return result
        except Exception as err:
            print(err)
            return False
    

    def __update_qry(self, tbl, param_arg, cond):
        try:
            db, cursor = self.__create_connection()

            qry = f"UPDATE {tbl} SET {param_arg}"

            if cond != "":
                qry += f" WHERE {cond}"

            cursor.execute(qry)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False
    

    def __insert_qry(self, tbl, params, vals, add_=""):
        try:
            db, cursor = self.__create_connection()

            qry = f"INSERT INTO {tbl} ({params}) VALUES ({vals}){add_}"

            cursor.execute(qry)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False
    

    def __update_worked_time(self, emp_no, date_now):
        try:
            db, cursor = self.__create_connection()

            qry = f"""
                    UPDATE attendance
                    SET 
                        worked_time = SEC_TO_TIME(
                            TIME_TO_SEC(TIMEDIFF(clock_out, clock_in)) - 
                            IF(TIME('12:00:00') BETWEEN TIME(clock_in) AND TIME(clock_out) OR 
                            TIME('13:00:00') BETWEEN TIME(clock_in) AND TIME(clock_out), 
                            3600, 0)
                        )
                    WHERE 
                        employee_no = '{emp_no}'
                        AND DATE = '{date_now}';
                    """


            cursor.execute(qry)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False
    
    def __calculate_ot(self, emp_no, date_now):
        clock_in, clock_out = self.get_int_out(emp_no, date_now)
        clock_in = clock_in.strftime('%Y-%m-%d %H:%M:%S')
        clock_out = clock_out.strftime('%Y-%m-%d %H:%M:%S')

        end_of_regular_hours = datetime.strptime(clock_in.split(' ')[0] + " 17:00:00", "%Y-%m-%d %H:%M:%S")
        ot_acknowledge_time = datetime.strptime(clock_in.split(' ')[0] + " 21:00:00", "%Y-%m-%d %H:%M:%S")
        ot_end_time = datetime.strptime(clock_in.split(' ')[0] + " 23:00:00", "%Y-%m-%d %H:%M:%S")
        
        clock_out_time = datetime.strptime(clock_out, "%Y-%m-%d %H:%M:%S")
        
        effective_clock_out = min(clock_out_time, ot_end_time)
        
        overtime_start = end_of_regular_hours
        overtime_duration = effective_clock_out - overtime_start
        
        if clock_out_time < ot_acknowledge_time:
            ot_time=  "00:00:00"
        else:
            ot_time = str(overtime_duration)

        try:
            db, cursor = self.__create_connection()

            qry = f"""
                    UPDATE attendance
                    SET `overtime` = '{ot_time}'
                    WHERE 
                        employee_no = '{emp_no}'
                        AND DATE = '{date_now}';
                    """


            cursor.execute(qry)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False


    def get_emp_name(self, emp_no):
        return self.__get_qry("CONCAT(first_name, ' ' , last_name) fullname",
                                "adding_employee",
                                f"employee_no = '{emp_no}'")[0][0]
    
    def get_attendance_status(self, emp_no, date_now):
        print(date_now)
        result = self.__get_qry("clock_in", "attendance", f'''
                                            `employee_no` = '{emp_no}'
                                            AND `date` = '{date_now}'
                                           ''')
        try:
            condition_ = len(result) == 0
        except Exception:
            condition_ = result == False
        return "In" if condition_ else "Out"

    def attendance_insert(self, emp_no, date_now, clock_in_date_time):
        status = self.get_attendance_status(emp_no, date_now)
        col = "clock_in" if status == "In" else "clock_out"
        now = datetime.now()

        if status == "In":
            new_status = "Late" if now.hour > 8 else "Present"
            self.__insert_qry("attendance",
                            f"employee_no, date, {col}, status",
                            f"'{emp_no}', '{date_now}', '{clock_in_date_time}', '{new_status}'")
        else:
            self.__update_qry("attendance",
                              f"clock_out = '{clock_in_date_time}'",
                              f'''
                                employee_no = '{emp_no}'
                                AND `date` = '{date_now}'
                               ''')
            self.__update_worked_time(emp_no, date_now)
            self.__calculate_ot(emp_no, date_now)
        return status


    
    def attendance_report(self, emp_no, status, current_date, time_):
        try:
            db, cursor = self.__create_connection()

            qry = f'''
                    INSERT INTO attendance_report (employee_no, employee_name, status, date, time_in)
                    VALUES ('{emp_no}',
                            (SELECT CONCAT(first_name, ' ', last_name) FROM adding_employee WHERE employee_no = '{emp_no}'),
                            '{status}', '{current_date}', '{time_}')
                    ON DUPLICATE KEY UPDATE
                        status = VALUES(status),
                        time_in = VALUES(time_in)
                    '''

            cursor.execute(qry)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False
        

    def transfer_attendance_to_report(self):
        try:
            db, cursor = self.__create_connection()
            current_date = datetime.now().date()

            qry1 = f'''
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
                    WHERE DATE(a.date) = '{current_date}'
                    ON DUPLICATE KEY UPDATE
                        status = VALUES(status),
                        time_in = VALUES(time_in),
                        time_out = VALUES(time_out),
                        actual_time = VALUES(actual_time)
                    '''
            qry2 = f"DELETE FROM attendance WHERE DATE(date) = '{current_date}'"

            cursor.execute(qry1)
            cursor.execute(qry2)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False
    
    def mark_absent_employees(self):
        try:
            db, cursor = self.__create_connection()
            current_date = datetime.now().date()

            qry1 = f'''
                    INSERT INTO attendance (employee_no, date, status)
                    SELECT e.employee_no, '{current_date}', 'Absent'
                    FROM adding_employee e
                    LEFT JOIN attendance a ON e.employee_no = a.employee_no AND DATE(a.date) = '{current_date}'
                    WHERE a.employee_no IS NULL
                    '''
            qry2 = f'''
                    INSERT INTO attendance_report (employee_no, employee_name, status, date)
                    SELECT e.employee_no, CONCAT(e.first_name, ' ', e.last_name), 'Absent', '{current_date}'
                    FROM adding_employee e
                    LEFT JOIN attendance_report ar ON e.employee_no = ar.employee_no AND DATE(ar.date) = '{current_date}'
                    WHERE ar.employee_no IS NULL
                    '''
            
            cursor.execute(qry1)
            cursor.execute(qry2)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False
        

    def __admin_login(self, username, password):
        try:
            db, cursor = self.__create_connection()

            qry = f'''
                    SELECT id FROM `admin`
                    WHERE `username` = '{username}'
                    AND `password` = ( SELECT MD5('{password}') )
                    '''

            cursor.execute(qry)
            result = cursor.fetchall()

            self.__closeConnection(cursor, db)

            return True if len(result) > 0 else False
        except Exception as err:
            print(err)
            return False
        

    def admin_login(self, username, password):
        return self.__admin_login(username, password)
    
    
    def generate_emp_no(self):
        yr_today = datetime.now().year

        while True:
            random_int = rd.randint(1111, 9999)
            emp_no = f"{yr_today}-{random_int}"
            try:
                db, cursor = self.__create_connection()

                qry = f'''
                        SELECT COUNT(*)
                        FROM `adding_employee`
                        WHERE `employee_no` = '{emp_no}'
                        '''
                cursor.execute(qry)
                result = cursor.fetchone()[0]
                self.__closeConnection(cursor, db)

                if result == 0:
                    break
            except Exception as err:
                print(err)
                return False
        return emp_no
    

    def get_positions(self):
        try:
            db, cursor = self.__create_connection()

            qry = f'''
                    SELECT rp.`rate_position` FROM `rate_position` rp
                    '''
            cursor.execute(qry)
            result = cursor.fetchall()
            self.__closeConnection(cursor, db)
            return [x[0] for x in result]
        except Exception as err:
            print(err)
            return False


    def get_int_out(self, emp_no, date_):
        try:
            db, cursor = self.__create_connection()

            qry = f'''
                    SELECT `clock_in`, `clock_out`
                    FROM `attendance`
                    WHERE `employee_no` = '{emp_no}'
                    AND `date` = '{date_}'
                    '''
            cursor.execute(qry)
            result = cursor.fetchall()
            self.__closeConnection(cursor, db)
            return result[0]
        except Exception as err:
            print(err)
            return False
    
    def save_new_emp(self, employee_no, last_name, 
                    first_name, middle_name, 
                    email, contact, rate_id, 
                    department, date_hired, 
                    address, face_samples):
        try:
            db, cursor = self.__create_connection()

            qry1 = f'''
                    INSERT INTO adding_employee (
                    employee_no, last_name, 
                    first_name, middle_name, 
                    email, contact, rate_id, 
                    department, date_hired, 
                    address, face_samples, 
                    face_descriptors, password_changed, 
                    password, birthdate, gender, 
                    nationality, emergency_contact_name, 
                    emergency_contact_number
                ) 
                VALUES (
                    '{employee_no}', 
                    '{first_name}', 
                    '{last_name}', 
                    '{middle_name}', 
                    '{email}', 
                    '{contact}', 
                    {rate_id}, 
                    '{department}', 
                    '{date_hired}', 
                    '{address}', 
                    {face_samples}, 
                    '', 
                    0, 
                    '', 
                    '0000-00-00', 
                    '', 
                    '', 
                    '', 
                    ''
                );
                    '''
     
            cursor.execute(qry1)
            db.commit()

            self.__closeConnection(cursor, db)

            return True
        except Exception as err:
            print(err)
            return False

    

if __name__ == "__main__":
    Db = Database_queries()
    print(Db.get_emp_name("EMP-6601"))
    #print(Db.attendance_insert("EMP-6601", "2024-11-15", "2024-11-16 18:53:26")) # working, auto detect if in or out
    # Db.attendance_insert("EMP-6601", "2024-11-20", "2024-11-20 17:00:00") #working
    # Db.attendance_report("EMP-6601", "Late", "2024-11-20", "08:15") 
    # Db.mark_absent_employees() #working
    # Db.transfer_attendance_to_report() #working
    #print(Db.get_attendance_status("EMP-6601", "2024-11-21")) #working
    #print(Db.admin_login("admin", 123123)) #working
    # print(Db.generate_emp_no()) #working
    #print(Db.get_positions()) #working
    # Db.save_new_emp("2024-1234", "A", "B", "C", "a@gmail", '09615953569',
    # 1,"IT", '2024-11-23', 'caloocan', 30) #working


