<?php
include_once 'loginCheck.php';
$pageTitle = 'Kalyan Chat Board';
$userName = $_SESSION['username'] ?? 'Guest';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($pageTitle) ?></title>
        <style>
            * { box-sizing: border-box; }
            body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f9; color: #1f2937; }
            .layout { display: flex; min-height: 100vh; }
            .sidebar { width: 230px; padding: 24px 16px; background: #1e3a8a; color: white; }
            .sidebar h2 { margin: 0 0 32px; font-size: 22px; }
            .sidebar a { display: block; padding: 12px; margin: 6px 0; border-radius: 6px; color: #dbeafe; text-decoration: none; }
            .sidebar a:hover, .sidebar a.active { background: #2563eb; color: white; }
            .content { flex: 1; padding: 28px; }
            header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
            header h1 { margin: 0 0 6px; font-size: 28px; }
            header p { margin: 0; color: #6b7280; }
            .profile { padding: 10px 16px; border-radius: 20px; background: white; box-shadow: 0 2px 8px #0000000d;text-transform: capitalize; }
            .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
            .card, .panel { padding: 22px; background: white; border-radius: 10px; box-shadow: 0 2px 8px #0000000d; }
            .card h3 { margin: 0 0 12px; color: #6b7280; font-size: 14px; font-weight: normal; }
            .card strong { font-size: 27px; }
            .panel { margin-top: 24px; }
            .panel h2 { margin-top: 0; font-size: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 13px 8px; border-bottom: 1px solid #e5e7eb; text-align: left; }
            th { color: #6b7280; font-size: 13px; }
            .status { color: #15803d; font-weight: bold; }
            @media (max-width: 800px) { .sidebar { width: 180px; } .stats { grid-template-columns: repeat(2, 1fr); } }
            @media (max-width: 550px) { .layout { display: block; } .sidebar { width: 100%; } .stats { grid-template-columns: 1fr; } header { align-items: flex-start; gap: 12px; flex-direction: column; } }
            .item-container { display: flex; flex-direction: row; justify-content: space-around; }
            .item-container div { margin: 0 5px; }
            .open-class { word-break: break-all; width: 10px; text-align: center; }
            .item-jodi { word-break: break-all; text-align: center; }
            .table-header { background-color: #f3f4f6; text-align: center; font-weight: bold; }
            .red-circle-badge {
                //background-color: #ff5600;  /* Pure red background */
                //color: #ffffff;             /* White text color for readability */
                display: inline-flex;       /* Enables flex centering inside the circle */
                align-items: center;        /* Centers text vertically */
                justify-content: center;    /* Centers text horizontally */
                width: 30px;                /* Equal width and height create a perfect circle */
                height: 30px;
                border-radius: 50%;         /* Makes the square background round */
                font-size: 15px;            /* Adjust font size as needed */
            }
            .table-container {
                width: 100%;             /* Ensures the container takes up the full screen width */
                overflow-x: auto;        /* Adds a horizontal scrollbar only when the table is wider than the screen */
                overflow-y: hidden;      /* Prevents unnecessary vertical scrolling blocks */
                -webkit-overflow-scrolling: touch; /* Ensures smooth, kinetic scrolling on iOS devices */
            }

            table {
                width: 100%;             /* Allows the table to expand naturally */
                max-width: none;         /* Prevents other styles from clipping the layout */
            }
            @media (max-width: 800px) {
                .content { padding: 5px 0px; }
                .card, .panel {
                    padding: 0px;  /* Reduces padding for smaller screens */
                }
            }
            .mondayInput, .tuesdayInput, .wednesdayInput, .thursdayInput, .fridayInput, .saturdayInput {
                margin-bottom: 5px; /* Adds space between the input fields */
                width: 130px; /* Sets a fixed width for the input fields */
            }

        </style>
    </head>
    <body>
        <div class="layout">
            <?php
            include 'mainMenu.php';
            ?>

            <main class="content">
                <header>
                    <div>
                        <h1><?= htmlspecialchars($pageTitle) ?></h1>
                        <p class="profile" >Welcome back, <?= htmlspecialchars($userName) ?>.</p>
                    </div>
                    <div class="profile">👤 <?= htmlspecialchars($userName) ?></div>
                </header>
                <section class="panel">
                    <h3 style="margin: 0;">Chat Board</h3>
                    <div id="chatData" class="table-container">
                        <form>
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center; padding: 12px; color: #6b7280;">                                            
                                            <input type="text" placeholder="Open Monday" name="MondayOpen" class="mondayInput"><br>
                                            <input type="text" placeholder="Close Monday" name="MondayClose" class="mondayInput"><br>
                                            <input type="text" placeholder="Jodi Monday" name="MondayJodi" class="mondayInput">                                         
                                        </td>
                                        <td style="text-align: center; padding: 12px; color: #6b7280;">
                                            <input type="text" placeholder="Open Tuesday" name="TuesdayOpen" class="tuesdayInput"><br>
                                            <input type="text" placeholder="Close Tuesday" name="TuesdayClose" class="tuesdayInput"><br>
                                            <input type="text" placeholder="Jodi Tuesday" name="TuesdayJodi" class="tuesdayInput">
                                        </td>
                                        <td style="text-align: center; padding: 12px; color: #6b7280;">
                                            <input type="text" placeholder="Open Wednesday" name="WednesdayOpen" class="wednesdayInput"><br>
                                            <input type="text" placeholder="Close Wednesday" name="WednesdayClose" class="wednesdayInput"><br>
                                            <input type="text" placeholder="Jodi Wednesday" name="WednesdayJodi" class="wednesdayInput">
                                        </td>
                                        <td style="text-align: center; padding: 12px; color: #6b7280;">
                                            <input type="text" placeholder="Open Thursday" name="ThursdayOpen" class="thursdayInput"><br>
                                            <input type="text" placeholder="Close Thursday" name="ThursdayClose" class="thursdayInput"><br>
                                            <input type="text" placeholder="Jodi Thursday" name="ThursdayJodi" class="thursdayInput">
                                        </td>
                                        <td  style="text-align: center; padding: 12px; color: #6b7280;">
                                            <input type="text" placeholder="Open Friday" name="FridayOpen" class="fridayInput"><br>
                                            <input type="text" placeholder="Close Friday" name="FridayClose" class="fridayInput"><br>
                                            <input type="text" placeholder="Jodi Friday" name="FridayJodi" class="fridayInput">
                                        </td>
                                        <td  style="text-align: center; padding: 12px; color: #6b7280;">
                                            <input type="text" placeholder="Open Saturday" name="SaturdayOpen" class="saturdayInput"><br>
                                            <input type="text" placeholder="Close Saturday" name="SaturdayClose" class="saturdayInput"><br>
                                            <input type="text" placeholder="Jodi Saturday" name="SaturdayJodi" class="saturdayInput">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                        <table>
                            <thead>
                                <tr>
                                    <th class="table-header">Date</th>
                                    <th class="table-header">
                                        <div>
                                        <div>Monday</div>
                                        <div
                                    </th>
                                    <th class="table-header">Tuesday</th>
                                    <th class="table-header">Wednesday</th>
                                    <th class="table-header">Thursday</th>
                                    <th class="table-header">Friday</th>
                                    <th class="table-header">Saturday</th>
                                </tr>
                            </thead>
                            <tbody id="chatTableBody">
                                <!-- Chat messages will be dynamically inserted here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 12px; color: #6b7280;">End of Chat Board</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </main>
        </div>
        <script src="./public/js/kalyanChatBoard.js"></script>
    </body>    
</html>