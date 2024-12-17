<?php

if (isset($_POST['generate_pdf'])) {
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=reportspdf.pdf");
    header("Cache-Control: no-cache");

    // Start output buffering
    ob_start();

    // Create a simple PDF structure
    echo "<h1>Reports</h1>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr>
            <th>Ticket#</th>
            <th>Client Name</th>
            <th>Agent Name</th>
            <th>Concern</th>
            <th>Severity</th>
            <th>Date Start</th>
            <th>Date End</th>
            <th>Status</th>
          </tr>";
    
    foreach ($reports as $report) {
        echo "<tr>
                <td>{$report['Ticket_num']}</td>
                <td>{$report['Client_Name']}</td>
                <td>{$report['Agent_Aname']}</td>
                <td>{$report['Concern']}</td>
                <td>{$report['Severity']}</td>
                <td>{$report['Date_start']}</td>
                <td>{$report['Date_end']}</td>
                <td>{$report['Status']}</td>
              </tr>";
    }
    
    echo "</table>";

    // Get the contents of the buffer
    $pdf_content = ob_get_clean();

    // Output the PDF content
    echo $pdf_content;

    exit; // Stop further execution
}

?>