import customtkinter as ctk
from tkcalendar import DateEntry
import cv2
from database_queries import Database_queries
import face_capture as fc
import time as tm

Dq = Database_queries()

def list_cameras():
    camera_list = []
    for i in range(10):
        cap = cv2.VideoCapture(i)
        if cap.isOpened():
            ret, _ = cap.read()
            if ret:
                camera_list.append(i)
            cap.release()
    return camera_list

def open_main_app():
    login_window.destroy()

    app = ctk.CTk()
    app.geometry("700x400")
    app.title("Employee Registration")
    app.iconbitmap("logo.ico")
    app.resizable(False, False)

    cams = [f"Cam {cam}" for cam in list_cameras()]

    title_label = ctk.CTkLabel(app, text="Employee Registration", font=("Arial", 20, "bold"))
    title_label.grid(row=0, column=0, columnspan=4, pady=10, sticky="w")

    emp_no_label = ctk.CTkLabel(app, text="Employee No.")
    emp_no_label.grid(row=1, column=0, padx=10, pady=5, sticky="w")
    emp_no_entry = ctk.CTkEntry(app)
    emp_no_entry.grid(row=1, column=1, padx=10, pady=5, sticky="w")

    def update_emp_no():
        emp_no_entry.configure(state="normal")
        emp_no_entry.delete(0, len(emp_no_entry.get()))
        emp_no_entry.insert(0, Dq.generate_emp_no())
        emp_no_entry.configure(state="disabled")
    
    update_emp_no()

    last_name_label = ctk.CTkLabel(app, text="Last Name")
    last_name_label.grid(row=1, column=2, padx=10, pady=5, sticky="w")
    last_name_entry = ctk.CTkEntry(app)
    last_name_entry.grid(row=1, column=3, padx=10, pady=5, sticky="w")

    first_name_label = ctk.CTkLabel(app, text="First Name")
    first_name_label.grid(row=2, column=0, padx=10, pady=5, sticky="w")
    first_name_entry = ctk.CTkEntry(app)
    first_name_entry.grid(row=2, column=1, padx=10, pady=5, sticky="w")

    middle_name_label = ctk.CTkLabel(app, text="Middle Name (Optional)")
    middle_name_label.grid(row=2, column=2, padx=10, pady=5, sticky="w")
    middle_name_entry = ctk.CTkEntry(app)
    middle_name_entry.grid(row=2, column=3, padx=10, pady=5, sticky="w")

    email_label = ctk.CTkLabel(app, text="Email (optional)")
    email_label.grid(row=3, column=0, padx=10, pady=5, sticky="w")
    email_entry = ctk.CTkEntry(app)
    email_entry.grid(row=3, column=1, padx=10, pady=5, sticky="w")

    contact_label = ctk.CTkLabel(app, text="Contact No.")
    contact_label.grid(row=3, column=2, padx=10, pady=5, sticky="w")
    contact_entry = ctk.CTkEntry(app)
    contact_entry.grid(row=3, column=3, padx=10, pady=5, sticky="w")

    position_label = ctk.CTkLabel(app, text="Position")
    position_label.grid(row=4, column=0, padx=10, pady=5, sticky="w")
    job_positions = Dq.get_positions()
    position_dropdown = ctk.CTkOptionMenu(app, values=["Select Position"] + job_positions)
    position_dropdown.grid(row=4, column=1, padx=10, pady=5, sticky="w")

    department_label = ctk.CTkLabel(app, text="Department")
    department_label.grid(row=4, column=2, padx=10, pady=5, sticky="w")
    department_dropdown = ctk.CTkOptionMenu(app, values=["Select Department",
                                                         "Human Resources",
                                                         "Information Technology",
                                                         "Finance",
                                                         "Marketing",
                                                         "Sales",
                                                         "Operations"])
    department_dropdown.grid(row=4, column=3, padx=10, pady=5, sticky="w")

    date_hired_label = ctk.CTkLabel(app, text="Date Hired")
    date_hired_label.grid(row=5, column=0, padx=10, pady=5, sticky="w")

    date_hired_picker = DateEntry(app, width=17, background="darkblue", foreground="white", borderwidth=2)
    date_hired_picker.grid(row=5, column=1, padx=10, pady=5, sticky="w")

    address_label = ctk.CTkLabel(app, text="Address")
    address_label.grid(row=5, column=2, padx=10, pady=5, sticky="w")
    address_entry = ctk.CTkTextbox(app, height=50)
    address_entry.grid(row=5, column=3, padx=10, pady=5, rowspan = 2, sticky="w")

    camera_label = ctk.CTkLabel(app, text="Select Camera")
    camera_label.grid(row=6, column=0, padx=10, pady=5, sticky="w")
    camera_dropdown = ctk.CTkOptionMenu(app, values=cams)
    camera_dropdown.grid(row=6, column=1, padx=10, pady=5, sticky="w")

    stat_ = ctk.CTkLabel(app, text="")
    stat_.grid(row=7, column=0, columnspan=4, pady=1)

    def register_emp():
        field_check_list = {
            "Last Name" : last_name_entry.get(),
            "First Name" : first_name_entry.get(),
            "Position" : position_dropdown.get(),
            "Contact No." : contact_entry.get(),
            "Department" : department_dropdown.get(),
            "Address": address_entry.get("0.0", "end")
        }
        emp_no = emp_no_entry.get()
        field_pass = 0
        for field in field_check_list:
            val_ = field_check_list[field]
            if len(val_) != 0 and val_ != "Select Department" and val_ != "Select Position" and val_ != "\n":
                field_pass += 1
            else:
                stat_.configure(text = f"{field} is Required!")

        if field_pass == len(field_check_list):
            cam_pos = int(camera_dropdown.get().replace("Cam ", ""))
            while True:
                num_samples = fc.capture_faces(emp_no_entry.get(), cam_pos)
                if num_samples == 30:
                    break

            Dq.save_new_emp(
                emp_no,
                field_check_list["Last Name"],
                field_check_list["First Name"],
                middle_name_entry.get(),
                email_entry.get(),
                field_check_list["Contact No."],
                job_positions.index(field_check_list["Position"]) + 1,
                field_check_list["Department"],
                date_hired_picker.get(),
                field_check_list["Address"],
                num_samples
            )
            update_emp_no()
            last_name_entry.delete(0, len(last_name_entry.get()))
            first_name_entry.delete(0, len(first_name_entry.get()))
            contact_entry.delete(0, len(contact_entry.get()))
            middle_name_entry.delete(0, len(middle_name_entry.get()))
            email_entry.delete(0, len(email_entry.get()))
            address_entry.delete("0.0", "end")
            stat_.configure(text = f"Saved")
            tm.sleep(2)
            stat_.configure(text = f"")

        print(field_check_list)

    register_button = ctk.CTkButton(app, text="Register Employee", fg_color="green", text_color="white", command=register_emp)
    register_button.grid(row=8, column=0, columnspan=4, pady=20)

    app.mainloop()


def handle_login():
    username = username_entry.get()
    password = password_entry.get()

    if Dq.admin_login(username, password):
        open_main_app()
    else:
        error_label.configure(text="Invalid username or password")


login_window = ctk.CTk()
login_window.geometry("250x330")
login_window.title("Admin Login")
login_window.iconbitmap("logo.ico")
login_window.resizable(False, False)

login_frame = ctk.CTkFrame(login_window, corner_radius=10)
login_frame.grid(pady=20, padx=20, sticky="nsew")  

login_window.grid_columnconfigure(0, weight=1)
login_window.grid_rowconfigure(0, weight=1)

login_frame.grid_columnconfigure(0, weight=1)

login_label = ctk.CTkLabel(login_frame, text="Admin Login", font=("Arial", 18, "bold"))
login_label.grid(row=0, column=0, pady=10)

username_label = ctk.CTkLabel(login_frame, text="Username:")
username_label.grid(row=1, column=0, pady=5, sticky="w", padx=10)
username_entry = ctk.CTkEntry(login_frame, width=200)
username_entry.grid(row=2, column=0, pady=(5,0))

password_label = ctk.CTkLabel(login_frame, text="Password:")
password_label.grid(row=3, column=0, pady=(5, 0), sticky="w", padx=10)
password_entry = ctk.CTkEntry(login_frame, show="*", width=200)
password_entry.grid(row=4, column=0, pady=5)

login_button = ctk.CTkButton(login_frame, text="Login", command=handle_login)
login_button.grid(row=5, column=0, pady=5)

error_label = ctk.CTkLabel(login_frame, text="")
error_label.grid(row=6, column=0, pady=5)

login_window.mainloop()
