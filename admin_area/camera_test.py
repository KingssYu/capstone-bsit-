import cv2

def test_camera(index):
    cap = cv2.VideoCapture(index)
    if not cap.isOpened():
        print(f"Failed to open camera at index {index}")
        return
    
    print(f"Successfully opened camera at index {index}")
    
    while True:
        ret, frame = cap.read()
        if not ret:
            print("Failed to capture frame")
            break
        
        cv2.imshow(f'Camera {index}', frame)
        
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break
    
    cap.release()
    cv2.destroyAllWindows()

# Test webcam
print("Testing webcam...")
test_camera(0)

# Test USB camera (try different indices)
for i in range(1, 5):
    print(f"Testing USB camera at index {i}...")
    test_camera(i)