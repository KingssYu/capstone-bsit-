<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        /* Sidebar Navigation */
        .sidenav {
            height: 100vh;
            width: 250px;
            background-color: #D5ED9F;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Logo Section */
        .sidenav .logo {
            padding: 20px;
            text-align: center;
            background-color: #D5ED9F;
            border-bottom: 1px solid #D5ED9F;
        }

        .sidenav .logo img {
            max-width: 80%;
            height: auto;
        }

        /* Menu Buttons */
        .sidenav .menu {
            flex: 1;
        }

        .sidenav .menu button {
            width: 100%;
            padding: 15px;
            border: none;
            background: #185519;
            color: #fff;
            text-align: left;
            font-size: 16px;
            cursor: pointer;
            border-bottom: 1px solid #ffffff;
            transition: background-color 0.3s ease;
        }

        .sidenav .menu button:hover {
            background: #00712D;
        }

        /* Footer Buttons */
        .sidenav .footer {
            padding: 20px;
            background-color: #D5ED9F;
            text-align: center;
        }

        .sidenav .footer button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #185519;
            border-radius: 50px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidenav .footer button:hover {
            background: #00712D;
        }

        /* Main Content Area */
        .content {
            margin-left: 250px;
            /* Adjusted to account for the sidebar */
            padding: 20px;
            width: calc(100% - 250px);
            background-color: #f4f4f4;
        }

        /* Tab Navigation */
        .tab-container {
            display: flex;
            border-bottom: 2px solid #185519;
            margin-bottom: 20px;
        }

        .tab-container button {
            padding: 10px 20px;
            border: none;
            background-color: #f1f1f1;
            color: #333;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .tab-container button:hover {
            background-color: #e2e2e2;
        }

        .tab-container button.active {
            background-color: #185519;
            color: #fff;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Table for Leave Requests */
        .leave-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .leave-table th,
        .leave-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .leave-table th {
            background-color: #185519;
            color: white;
        }

        .leave-table td img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        /* Action Buttons */
        .action-btn {
            background-color: #00712D;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #004d1f;
        }

        .action-btn.denied {
            background-color: red;
        }

        .action-btn.denied:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include './header.php'; ?>


    <!-- Main Content for Leave Management -->
    <div class="content">
        <h2>Leave Management</h2>

        <!-- Tab Navigation -->
        <div class="tab-container">
            <button class="tab-btn active" onclick="openTab('requests')">Requests</button>
            <button class="tab-btn" onclick="openTab('approved')">Approved</button>
            <button class="tab-btn" onclick="openTab('denied')">Denied</button>
        </div>

        <!-- Tab Content -->
        <div id="requests" class="tab-content active">
            <h3>Requests</h3>
            <table class="leave-table">
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Date Requested</th>
                        <th>Hours Requested</th>
                        <th>Request Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="./employee_profile/profiles.jpg" alt="Profile"> Castillo, Kristine Grace</td>
                        <td>Oct 10 - Oct 12</td>
                        <td>24h</td>
                        <td>Oct 8</td>
                        <td><button class="action-btn">Review Request</button></td>
                    </tr>
                    <tr>
                        <td><img src="./employee_profile/profiles.jpg" alt="Profile"> Yu, King Mark</td>
                        <td>Oct 16 - Oct 17</td>
                        <td>12h</td>
                        <td>Oct 10</td>
                        <td><button class="action-btn">Review Request</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="approved" class="tab-content">
            <h3>Approved</h3>
            <table class="leave-table">
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Date Requested</th>
                        <th>Hours Requested</th>
                        <th>Request Submitted</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="profile1.jpg" alt="Profile"> Castillo, Kristine Grace</td>
                        <td>Oct 10 - Oct 12</td>
                        <td>24h</td>
                        <td>Oct 8</td>
                        <td><button class="action-btn">Approve</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="denied" class="tab-content">
            <h3>Denied</h3>
            <table class="leave-table">
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Date Requested</th>
                        <th>Hours Requested</th>
                        <th>Request Submitted</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="profile2.jpg" alt="Profile"> Yu, King Mark</td>
                        <td>Oct 16 - Oct 17</td>
                        <td>12h</td>
                        <td>Oct 10</td>
                        <td><button class="action-btn denied">Denied</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // JavaScript function to toggle between tabs
        function openTab(tabId) {
            // Hide all tab content
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(button => button.classList.remove('active'));

            // Show the clicked tab content and add active class to the button
            document.getElementById(tabId).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>

</html>