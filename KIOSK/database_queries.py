import mysql.connector
from datetime import datetime, timedelta, time



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
    rounded_overtime = round(overtime_hours * 2) / 2
    return timedelta(hours=rounded_overtime)



class Database_queries:
    def __init__(self):
        with open("config.txt") as file:
            __conf = [x.replace("\n", "") for x in file.readlines()]

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
    

if __name__ == "__main__":
    Db = Database_queries()
    print(Db.get_emp_name("EMP-00029"))
    #print(Db.attendance_insert("EMP-6601", "2024-11-21", "2024-11-21 18:00:00")) # working, auto detect if in or out
    # Db.attendance_insert("EMP-6601", "2024-11-20", "2024-11-20 17:00:00") #working
    # Db.attendance_report("EMP-6601", "Late", "2024-11-20", "08:15") 
    # Db.mark_absent_employees() #working
    # Db.transfer_attendance_to_report() #working
    #print(Db.get_attendance_status("EMP-6601", "2024-11-21"))