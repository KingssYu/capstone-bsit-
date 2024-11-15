import os
import cv2
import face_recognition
import numpy as np

def convert_existing_images():
    path = 'employee_faces'
    for employee_folder in os.listdir(path):
        employee_path = os.path.join(path, employee_folder)
        if os.path.isdir(employee_path):
            image_file = os.path.join(employee_path, 'face_1.jpg')  # Assuming current images are JPG
            if os.path.exists(image_file):
                image = cv2.imread(image_file)
                if image is not None:
                    # Convert to RGB
                    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
                    
                    # Save as PNG
                    cv2.imwrite(os.path.join(employee_path, 'face_1.png'), cv2.cvtColor(image_rgb, cv2.COLOR_RGB2BGR))
                    
                    # Create and save face encoding
                    encodings = face_recognition.face_encodings(image_rgb)
                    if len(encodings) > 0:
                        np.save(os.path.join(employee_path, 'face_encoding.npy'), encodings[0])
                    
                    # Remove old JPG file
                    os.remove(image_file)
                    print(f"Converted image for {employee_folder}")
                else:
                    print(f"Failed to load image for {employee_folder}")
            else:
                print(f"No image found for {employee_folder}")

if __name__ == "__main__":
    convert_existing_images()