// Function to check tasks nearing their due date
function checkDueDates() {
    $.ajax({
        url: 'fetch_tasks.php', // Fetch all tasks with their due dates
        type: 'GET',
        success: function(response) {
            const tasks = JSON.parse(response); // Assuming your PHP returns JSON with task details

            // Loop through the tasks to check their due date
            tasks.forEach(task => {
                const currentDate = new Date();
                const dueDate = new Date(task.expected_delivery_date);
                const timeDifference = dueDate - currentDate;

                // Check if the task is within 1 day of its due date
                if (timeDifference <= 24 * 60 * 60 * 1000 && timeDifference > 0) {
                    // Display a notification if the task is nearing the due date
                    displayDueDateReminder(task);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching tasks:", error);
        }
    });
}

// Function to display a reminder notification
function displayDueDateReminder(task) {
    toastr.info(`Reminder: The task "${task.task}" is nearing its due date of ${task.expected_delivery_date}.`, 'Task Reminder', {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    });
}

// Set an interval to check for due date reminders every hour (3600000 ms = 1 hour)
setInterval(checkDueDates, 3600000);

// Also, call it when the page loads initially
$(document).ready(function() {
    checkDueDates(); // Run this on page load
});