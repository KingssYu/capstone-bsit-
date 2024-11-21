import tkinter
from PIL import Image, ImageTk
import customtkinter as ctk
from datetime import datetime, timedelta
import cv2
import os
# import face_recognition
import logging
import numpy as np
from database_queries import Database_queries

Dq = Database_queries()

ctk.set_appearance_mode("Light")
ctk.set_default_color_theme("blue")


config_file_name = "config.txt"
config_file_exist = os.path.exists(config_file_name)

global_data = False


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


def load_global_data():
    if config_file_exist:
        global known_face_encodings, known_face_names, face_cooldowns, cooldown_duration, global_data
        with open(config_file_name) as file:

            base_path = file.read().split("\n")[-2].replace("/", "\\").replace("\\")
            known_face_encodings, known_face_names = load_employee_faces(base_path)

            face_cooldowns = {face_: datetime.min for face_ in known_face_names}
            cooldown_duration = timedelta(seconds=10)

            global_data = True

    

load_global_data()

def handle_new_face_detected(new_face):
    for face_ in face_cooldowns:
        face_cooldowns[face_] = datetime.min 

    face_cooldowns[new_face] = datetime.now() + cooldown_duration

def can_process_face(face_):
    return datetime.now() >= face_cooldowns.get(face_, datetime.min)


class App(ctk.CTk):
    def __init__(self):
        super().__init__()
        self.emp_id = "ID: "
        self.emp_name = "Name: "
        self.emp_status = "Status: "

        self.title("Facial Recognition Kiosk")
        self.iconbitmap("logo.ico")
        self.geometry(f"{892}x{533}")
        self.configure(fg_color="#FFFBE6")

        self.grid_columnconfigure(0, weight=1) 
        self.grid_rowconfigure(0, weight=15)
        self.grid_rowconfigure(1, weight=100)

        self.header_frame = ctk.CTkFrame(self, fg_color="#FFFBE6")
        self.header_frame.grid(row=0, column=0, sticky="nsew", padx=20, pady=10)
        self.header_frame.grid_columnconfigure(0, weight=100)
        self.header_frame.grid_columnconfigure(1, weight=10)
        self.header_frame.grid_rowconfigure((0, 1), weight=1)

        self.date_label = ctk.CTkLabel(self.header_frame, text="", font=("Inter", 32, "bold"))
        self.date_label.grid(row=0, column=0, sticky="nsw")

        self.time_label = ctk.CTkLabel(self.header_frame, text="", font=("Inter", 32, "bold"))
        self.time_label.grid(row=1, column=0, sticky="nsw")

        self.btn_frame = ctk.CTkFrame(self.header_frame, fg_color="#FFFBE6")
        self.btn_frame.grid(row=0, column=1, sticky="e")

        def call_open_config():
            self.open_config()

        self.configure_btn = ctk.CTkButton(self.btn_frame, text="Config", fg_color="#FCCD2A", text_color="black" , font=("Inter", 15, "bold"), command=call_open_config)
        self.configure_btn.grid(row=0, column=0, sticky="nsew")

        self.body_frame = ctk.CTkFrame(self, fg_color="#FFFBE6") 
        self.body_frame.grid(row=1, column=0, sticky="nsew", padx=20, pady=(0, 50))
        self.body_frame.grid_columnconfigure(0, weight=100)
        self.body_frame.grid_columnconfigure(1, weight=40)
        self.body_frame.grid_rowconfigure(0, weight=1)

        self.cam_outer_frame = ctk.CTkFrame(self.body_frame, fg_color="#347928")
        self.cam_outer_frame.grid(row=0, column=0, sticky="nsew")
        self.cam_outer_frame.grid_columnconfigure(0, weight=1)
        self.cam_outer_frame.grid_rowconfigure(0, weight=1)

        self.cam_inner_frame = ctk.CTkFrame(self.cam_outer_frame, fg_color="#FFFBE6")
        self.cam_inner_frame.grid(row=0, column=0, sticky="nsew", padx=5, pady=5)

        self.image_label = ctk.CTkLabel(self.cam_inner_frame, text="")
        self.image_label.pack(expand=True, fill="both")

        self.cap = cv2.VideoCapture(1)
        self.update_video_frame()

        self.emp_logs_frame = ctk.CTkFrame(self.body_frame, fg_color="#FFFBE6")
        self.emp_logs_frame.grid(row=0, column=1, sticky="nsew", padx=10, pady=10)
        self.emp_logs_frame.grid_columnconfigure(0, weight=1)
        self.emp_logs_frame.grid_rowconfigure((0, 2, 4), weight=100)
        self.emp_logs_frame.grid_rowconfigure((1, 3), weight=1)

        self.emp_id_disp = ctk.CTkLabel(self.emp_logs_frame, text=self.emp_id, font=("Inter", 20))
        self.emp_id_disp.grid(row=0, column=0, sticky="nsw", pady=10)

        self.emp_divider = ctk.CTkFrame(self.emp_logs_frame, fg_color="#347928", height=1)
        self.emp_divider.grid(row=1, column=0, sticky="nsew", padx=10, pady=10)

        self.emp_name_disp = ctk.CTkLabel(self.emp_logs_frame, text=self.emp_name, font=("Inter", 20))
        self.emp_name_disp.grid(row=2, column=0, sticky="nsw", pady=10)

        self.emp_divider2 = ctk.CTkFrame(self.emp_logs_frame, fg_color="#347928", height=1)
        self.emp_divider2.grid(row=3, column=0, sticky="nsew", padx=10, pady=10)

        self.emp_status_disp = ctk.CTkLabel(self.emp_logs_frame, text=self.emp_status, font=("Inter", 20))
        self.emp_status_disp.grid(row=4, column=0, sticky="nsw", pady=10)

        self.update_date_time()

    def update_video_frame(self):
        global known_face_encodings, known_face_names, base_path
        ret, frame = self.cap.read()
        if ret:
            frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            pil_image = Image.fromarray(frame_rgb)
            
            ctk_image = ctk.CTkImage(light_image=pil_image, size=(640, 480))
            
            self.image_label.configure(image=ctk_image)
            self.image_label.image = ctk_image

            if config_file_exist:
                if not global_data:
                    load_global_data
                face_locations = face_recognition.face_locations(frame_rgb, model="hog")
                face_encodings = face_recognition.face_encodings(frame_rgb, face_locations)

                

                if not known_face_encodings:
                    logging.warning("No employee faces loaded. Exiting.")
                    return

                for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
                    face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
                    best_match_index = np.argmin(face_distances)
                    
                    if face_distances[best_match_index] < 0.6: 
                        employee_no = known_face_names[best_match_index]
                        current_datetime = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                        print(f"emp: {employee_no} - {current_datetime}")

                        if can_process_face(employee_no):
                            emp_name = Dq.get_emp_name(employee_no)
                            attendance_stat = Dq.attendance_insert(employee_no,
                                                current_datetime.split(" ")[0],
                                                current_datetime
                                                )
                            
                            self.emp_id_disp.configure(text = f"ID: {employee_no}")
                            self.emp_name_disp.configure(text = f"Name: {emp_name}")
                            self.emp_status_disp.configure(text = f"Status: {attendance_stat}")

                            handle_new_face_detected(employee_no)
                    # emp_name = Dq.get_emp_name(employee_no)
                    # attendance_stat = Dq.attendance_insert(employee_no,
                    #                     current_datetime.split(" ")[0],
                    #                     current_datetime
                    #                     )
                    
                    # self.emp_id_disp.configure(text = f"ID: {employee_no}")
                    # self.emp_name_disp.configure(text = f"Name: {emp_name}")
                    # self.emp_status_disp.configure(text = f"Status: {attendance_stat}")

                else:
                    pass
    

        self.after(10, self.update_video_frame) 

    def update_date_time(self):
        now = datetime.now()
        self.date_label.configure(text=f"Date: {now.strftime('%Y-%m-%d')}")
        self.time_label.configure(text=f"Time: {now.strftime('%H:%M:%S')}")
        self.after(1000, self.update_date_time)

    def open_config(self):
        if hasattr(self, "config_window") and self.config_window.winfo_exists():
            self.config_window.focus_set() 
            return

        config_window = ctk.CTkToplevel(self)
        config_window.iconbitmap("logo.ico")
        config_window.title("Config")
        config_window.geometry("380x257")
        config_window.configure(fg_color="#FFFBE6")
        config_window.transient(self)

        config_param = ["Host", "Username", "Password", "Port", "Database"]
        config_window.grid_columnconfigure(0, weight=1)
        config_window.grid_rowconfigure(0, weight=100)
        config_window.grid_rowconfigure(1, weight=20)

        label_input_cont = ctk.CTkFrame(config_window, fg_color="#FFFBE6")
        label_input_cont.grid(row=0, column=0)
        label_input_cont.grid_columnconfigure(0, weight=1)
        label_input_cont.grid_rowconfigure((0, 1, 2, 3, 4, 5), weight=1)

        config_label = {}
        config_input = {}
        img_path_txt = "img_path_input"
        for pos, conf in enumerate(config_param):
            config_label[f"{conf}_label"] = ctk.CTkLabel(label_input_cont, text=conf)
            config_label[f"{conf}_label"].grid(row=pos, column=0, padx=10, pady=5, sticky="w")

            config_input[f"{conf}_input"] = ctk.CTkEntry(label_input_cont, width=250, font=("Inter", 14), fg_color="#C0EBA6", border_color="#C0EBA6")
            config_input[f"{conf}_input"].grid(row=pos, column=1, padx=10, pady=5)
        
        def save_config():
            config = ''
            for input_ in config_input:
                config += f"{config_input[input_].get()}\n"

            with open(config_file_name, "w") as config_file:
                config_file.write(config)
                load_global_data()
        
        def get_img_path():
            print("img path")
        
        get_path_btn = ctk.CTkButton(label_input_cont, text="Img Path", fg_color="#FCCD2A", text_color="black" , font=("Inter", 14), command=get_img_path)
        get_path_btn.grid(row=5, column=0, sticky="nsew", padx=10, pady=5)

        config_input[img_path_txt] = ctk.CTkEntry(label_input_cont, width=250, font=("Inter", 14), fg_color="#C0EBA6", border_color="#C0EBA6")
        config_input[img_path_txt].grid(row=5, column=1, padx=10, pady=5, sticky="w")
        config_input[img_path_txt].configure(state="disabled")

        save_btn = ctk.CTkButton(config_window, text="Save", fg_color="#FCCD2A", text_color="black" , font=("Inter", 15, "bold"), command=save_config)
        save_btn.grid(row=1, column=0, sticky="nsew", padx=70, pady=20)

        if os.path.exists(config_file_name):
            with open(config_file_name, "r") as config_file:
                configuration = config_file.read()
                print(configuration)

if __name__ == "__main__":
    app = App()
    app.mainloop()
