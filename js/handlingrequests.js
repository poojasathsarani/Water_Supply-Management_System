document.addEventListener("DOMContentLoaded", function() {
    fetchRequests();
    fetchTechnicians(); // Fetch technicians for the dropdown

    // Add event listener for the "Maintenance Details" link in the sidebar
    const maintenanceDetailsLink = document.getElementById('maintenanceDetailsLink');
    maintenanceDetailsLink.addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('detailsBox').scrollIntoView({ behavior: 'smooth' });
    });
});

function fetchRequests() {
    // Sample data
    const sampleRequests = [
        {
            requestID: 1,
            consumerID: 101,
            consumerName: "John Doe",
            phoneNumber: "123-456-7890",
            maintenanceDetails: {
                datePeriod: "2024-07-01 to 2024-07-10",
                issueDescription: "Leaking pipe",
                location: "123 Main St"
            }
        },
        {
            requestID: 2,
            consumerID: 102,
            consumerName: "Jane Smith",
            phoneNumber: "987-654-3210",
            maintenanceDetails: {
                datePeriod: "2024-07-05 to 2024-07-12",
                issueDescription: "No water supply",
                location: "456 Elm St"
            }
        }
    ];

    const tableBody = document.querySelector("#requestsTable tbody");
    sampleRequests.forEach(request => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${request.requestID}</td>
            <td>${request.consumerID}</td>
            <td>${request.consumerName}</td>
            <td>${request.phoneNumber}</td>
            <td><button class="btn btn-info view-details-btn" data-request-id="${request.requestID}">Click here to view</button></td>
            <td>
                <select class="form-control technician-dropdown" data-request-id="${request.requestID}">
                    <option value="">Select Technician</option>
                    <option value="1">Technician 1</option>
                    <option value="2">Technician 2</option>
                </select>
            </td>
            <td><button class="btn btn-success confirm-btn" data-request-id="${request.requestID}">Confirm</button></td>
            <td>Pending</td>
        `;

        tableBody.appendChild(row);
    });

    // Add event listeners for the "Click here to view" buttons
    document.querySelectorAll(".view-details-btn").forEach(button => {
        button.addEventListener("click", function() {
            const requestID = this.dataset.requestId;
            const request = sampleRequests.find(req => req.requestID == requestID);
            displayDetails(request);
        });
    });

    // Add event listeners for the "Confirm" buttons
    document.querySelectorAll(".confirm-btn").forEach(button => {
        button.addEventListener("click", function() {
            const requestID = this.dataset.requestId;
            confirmRequest(requestID);
        });
    });
}

function displayDetails(request) {
    document.getElementById("detailDate").innerText = request.maintenanceDetails.datePeriod;
    document.getElementById("detailLocation").innerText = request.maintenanceDetails.location;
    document.getElementById("detailDescription").innerText = request.maintenanceDetails.issueDescription;
    document.getElementById('detailsBox').scrollIntoView({ behavior: 'smooth' });
}

function confirmRequest(requestID) {
    alert(`Request ID ${requestID} has been confirmed.`);
}

function fetchTechnicians() {
    // Sample data for technicians
    const sampleTechnicians = [
        { technicianID: 1, name: "Technician 1" },
        { technicianID: 2, name: "Technician 2" }
    ];

    document.querySelectorAll(".technician-dropdown").forEach(dropdown => {
        sampleTechnicians.forEach(technician => {
            const option = document.createElement("option");
            option.value = technician.technicianID;
            option.textContent = technician.name;
            dropdown.appendChild(option);
        });
    });
}

function searchRequest() {
    const searchValue = document.getElementById("searchRequestID").value.trim();
    const rows = document.querySelectorAll("#requestsTable tbody tr");
    rows.forEach(row => {
        const requestID = row.cells[0].innerText;
        if (requestID.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

function refreshRequests() {
    const rows = document.querySelectorAll("#requestsTable tbody tr");
    rows.forEach(row => {
        row.style.display = "";
    });
    document.getElementById("searchRequestID").value = "";
}
