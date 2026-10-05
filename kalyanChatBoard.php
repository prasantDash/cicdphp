<?php
include_once 'loginCheck.php';
$pageTitle = 'Kalyan Chat Board';
$userName = $_SESSION['username'] ?? 'Guest';
function isMobile() {
    return isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.browser|up\.link|vodafone|wap|windows ce|xda|xiino/i', $_SERVER['HTTP_USER_AGENT']);
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($pageTitle) ?></title>
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: #f4f6f9;
                color: #1f2937;
            }
            .layout {
                display: flex;
                min-height: 100vh;
            }
            .sidebar {
                width: 230px;
                padding: 24px 16px;
                background: #1e3a8a;
                color: white;
            }
            .sidebar h2 {
                margin: 0 0 32px;
                font-size: 22px;
            }
            .sidebar a {
                display: block;
                padding: 12px;
                margin: 6px 0;
                border-radius: 6px;
                color: #dbeafe;
                text-decoration: none;
            }
            .sidebar a:hover,
            .sidebar a.active {
                background: #2563eb;
                color: white;
            }
            .content {
                flex: 1;
                padding: 28px;
            }
            header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 28px;
            }
            header h1 {
                margin: 0 0 6px;
                font-size: 28px;
            }
            header p {
                margin: 0;
                color: #6b7280;
            }
            .profile {
                padding: 10px 16px;
                border-radius: 20px;
                background: white;
                box-shadow: 0 2px 8px #0000000d;
                text-transform: capitalize;
            }
            .stats {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 18px;
            }
            .card,
            .panel {
                padding: 22px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 8px #0000000d;
            }
            /* .card h3 {
                margin: 0 0 12px;
                color: #6b7280;
                font-size: 14px;
                font-weight: normal;
            }
            .card strong {
                font-size: 27px;
            } */
            .panel {
                margin-top: 24px;
            }
            .panel h2 {
                margin-top: 0;
                font-size: 20px;
            }
            th,
            td {
                padding: 13px 8px;
                border-bottom: 1px solid #e5e7eb;
                text-align: left;
            }
            th {
                color: #6b7280;
            }
            .status {
                color: #15803d;
                font-weight: bold;
            }
            @media (max-width: 800px) {
                .sidebar {
                    width: 180px;
                }
                .stats {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 550px) {
                .layout {
                    display: block;
                }
                .sidebar {
                    width: 100%;
                }
                .stats {
                    grid-template-columns: 1fr;
                }
                header {
                    align-items: flex-start;
                    gap: 12px;
                    flex-direction: column;
                }
            }
            .item-container {
                display: flex;
                flex-direction: row;
                justify-content: space-around;
            }
            .item-container div {
                margin: 0 5px;
            }
            .open-class {
                word-break: break-all;
                width: 10px;
                text-align: center;
            }
            .item-jodi {
                word-break: break-all;
                text-align: center;
            }
            .table-header {
                background-color: #f3f4f6;
                text-align: center;
                font-weight: bold;
            }

            .table-container {
                width: 100%; /* Ensures the container takes up the full screen width */
                overflow-x: auto; /* Adds a horizontal scrollbar only when the table is wider than the screen */
                overflow-y: hidden; /* Prevents unnecessary vertical scrolling blocks */
                -webkit-overflow-scrolling: touch; /* Ensures smooth, kinetic scrolling on iOS devices */
            }
            @media (max-width: 800px) {
                .content {
                    padding: 5px 0px;
                }
                .card,
                .panel {
                    padding: 0px; /* Reduces padding for smaller screens */
                }
                #search-chat-Form {
                    table-layout: fixed; /* Ensures the form fields fit within the screen width */
                    overflow-x: auto; /* Adds horizontal scrolling if necessary */
                }
                .table-cell {
                    padding: 5px; /* Reduces cell padding for smaller screens */
                    color: #6b7280; /* Maintains text color for readability */
                }
                th,
                td {
                    padding: 0px 0px;
                    border-bottom: 1px solid #e5e7eb;
                    text-align: left;
                    font-size: 10px;
                    height: 50px;
                    width: 47px;
                }
                .item-container div {
                    margin: 0 0px;
                }
            }
            .mondayInput,
            .tuesdayInput,
            .wednesdayInput,
            .thursdayInput,
            .fridayInput,
            .saturdayInput {
                margin-bottom: 5px; /* Adds space between the input fields */
                width: 130px; /* Sets a fixed width for the input fields */
            }
            .chart-board-header {
                display: flex;
                justify-content: space-between;
            }
            .mobile-table th,
            .mobile-table td {
                font-size: 10px !important; /* Reduces font size for better fit on smaller screens */
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
                </header>
                <section class="panel">
                    <div class="chart-board-header">
                        <div>
                            <h3 style="margin: 0;">Search Chat</h3>
                        </div>
                        <div>
                            <button type="button" id="searchButton">Search</button> 
                        </div>
                    </div>
                    
                    <div id="chatData" class="table-container">
                        <form id="search-chat-Form">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="table-cell" >                                            
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" placeholder="Open Monday" name="Monday-open" class="mondayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" max="99" placeholder="Jodi Monday" name="Monday-jodi" class="mondayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Close Monday" name="Monday-close" class="mondayInput">                                                                                
                                        </td>
                                        <td class="table-cell" >
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Open Tuesday" name="Tuesday-open" class="tuesdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" max="99" placeholder="Jodi Tuesday" name="Tuesday-jodi" class="tuesdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Close Tuesday" name="Tuesday-close" class="tuesdayInput">
                                        </td>
                                        <td class="table-cell" >
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Open Wednesday" name="Wednesday-open" class="wednesdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" max="99" placeholder="Jodi Wednesday" name="Wednesday-jodi" class="wednesdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Close Wednesday" name="Wednesday-close" class="wednesdayInput">
                                        </td>
                                        <td class="table-cell">
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Open Thursday" name="Thursday-open" class="thursdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" max="99" placeholder="Jodi Thursday" name="Thursday-jodi" class="thursdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Close Thursday" name="Thursday-close" class="thursdayInput">
                                            
                                        </td>
                                        <td class="table-cell">
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Open Friday" name="Friday-open" class="fridayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" max="99" placeholder="Jodi Friday" name="Friday-jodi" class="fridayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Close Friday" name="Friday-close" class="fridayInput">
                                            
                                        </td>
                                        <td class="table-cell">
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Open Saturday" name="Saturday-open" class="saturdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);" max="99" placeholder="Jodi Saturday" name="Saturday-jodi" class="saturdayInput"><br>
                                            <input type="number" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" max="999" placeholder="Close Saturday" name="Saturday-close" class="saturdayInput">                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                        <div id="search-result"></div>
                        <table id="mainChartBoard" border="1">
                            <thead>
                                <tr>
                                    <th class="table-header">Date</th>
                                    <th class="table-header"><?php echo (isMobile()) ? "Mon" : "Monday"; ?></th>
                                    <th class="table-header"><?php echo (isMobile()) ? "Tue" : "Tuesday"; ?></th>
                                    <th class="table-header"><?php echo (isMobile()) ? "Wed" : "Wednesday"; ?></th>
                                    <th class="table-header"><?php echo (isMobile()) ? "Thu" : "Thursday"; ?></th>
                                    <th class="table-header"><?php echo (isMobile()) ? "Fri" : "Friday"; ?></th>
                                    <th class="table-header"><?php echo (isMobile()) ? "Sat" : "Saturday"; ?></th>
                                </tr>
                            </thead>
                            <tbody id="chatTableBody"></tbody>
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