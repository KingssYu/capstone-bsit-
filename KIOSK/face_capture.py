import cv2
import os
import json
import sys
import numpy as np
import traceback
from tqdm import tqdm
import logging

config_file_name = "config.txt"
config_file_exist = os.path.exists(config_file_name)


if os.path.exists(config_file_name):
    with open(config_file_name) as file:
        base_path = file.readlines()[-1]
else:
    base_path = ""

logging.basicConfig(filename='face_capture.log', level=logging.DEBUG)

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

def capture_faces(employee_id, camera_index=0, num_samples=30):
    try:
        face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        cap = cv2.VideoCapture(camera_index)

        if not cap.isOpened():
            logging.error(f"Error: Could not open camera with index {camera_index}")
            raise Exception(f"Error: Could not open camera with index {camera_index}")

        face_samples = []
        sample_count = 0
        attempts = 0
        max_attempts = 300

        cv2.namedWindow('Face Capture', cv2.WINDOW_NORMAL)
        cv2.resizeWindow('Face Capture', 640, 480)

        pbar = tqdm(total=num_samples, desc="Capturing faces", ncols=70)

        while sample_count < num_samples and attempts < max_attempts:
            ret, frame = cap.read()
            if not ret:
                logging.error("Failed to capture frame")
                raise Exception("Failed to capture frame")
            
            gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
            try:
                faces = face_cascade.detectMultiScale(gray, 1.3, 5)
            except Exception as e:
                logging.error(f"Error in face detection: {str(e)}")
                continue
            
            if len(faces) > 0:
                for (x, y, w, h) in faces:
                    cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
                    face = frame[y:y+h, x:x+w]
                    face_sample = cv2.resize(face, (224, 224))
                    _, buffer = cv2.imencode('.jpg', face_sample)
                    face_samples.append(buffer.tobytes())
                    sample_count += 1
                    pbar.update(1)
                    if sample_count >= num_samples:
                        break
            else:
                cv2.putText(frame, "No face detected", (20, 50), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)

            bordered_frame = cv2.copyMakeBorder(frame, 50, 10, 10, 10, cv2.BORDER_CONSTANT, value=[255, 255, 255])
            cv2.putText(bordered_frame, "Face Capture System", (20, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 0), 2)

            progress_text = f"Progress: {sample_count}/{num_samples}"
            cv2.putText(bordered_frame, progress_text, (20, bordered_frame.shape[0] - 20), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 0), 1)

            center = (bordered_frame.shape[1] - 30, 30)
            radius = int(15 + 5 * np.sin(attempts * 0.2))
            cv2.circle(bordered_frame, center, radius, (0, 165, 255), -1)

            cv2.imshow('Face Capture', bordered_frame)
            if cv2.waitKey(1) & 0xFF == ord('q'):
                break
            
            attempts += 1

        pbar.close()
        cap.release()
        cv2.destroyAllWindows()

        if sample_count < num_samples:
            logging.warning(f"Could only capture {sample_count} samples out of {num_samples}")
            raise Exception(f"Could only capture {sample_count} samples out of {num_samples}")

        employee_folder = f"{base_path}/employee_faces/{employee_id}".replace("/", "\\")
        os.makedirs(employee_folder, exist_ok=True)

        for i, sample in enumerate(face_samples):
            with open(f'{employee_folder}/face_{i+1}.jpg', 'wb') as f:
                f.write(sample)

        logging.info(f"Saved {len(face_samples)} face samples for employee {employee_id}")
        return len(face_samples)
    except Exception as e:
        error_message = f"Error in capture_faces: {str(e)}"
        logging.error(error_message)
        logging.error(traceback.format_exc())
        return 0

if __name__ == "__main__":
    try:
        if len(sys.argv) < 2:
            print(json.dumps({"error": "Usage: python face_capture.py <employee_id> [camera_index]"}))
            sys.exit(1)

        if sys.argv[1] == "list_cameras":
            cameras = list_cameras()
            print(json.dumps({"cameras": cameras}))
        else:
            employee_id = sys.argv[1]
            camera_index = int(sys.argv[2]) if len(sys.argv) > 2 else 0
            num_samples = capture_faces(employee_id, camera_index)
            print(json.dumps({"num_samples": num_samples}))
    except Exception as e:
        error_message = f"Error in main: {str(e)}"
        logging.error(error_message)
        logging.error(traceback.format_exc())
        print(json.dumps({"error": error_message}))