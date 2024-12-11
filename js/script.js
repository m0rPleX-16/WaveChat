
document.addEventListener('DOMContentLoaded', function () {
    const privateSmsForm = document.getElementById('privateSmsForm');
    if (privateSmsForm) {
        privateSmsForm.addEventListener('submit', function (e) {
            const phoneInput = document.getElementById('phone_number');
            const messageInput = document.getElementById('message');

            if (!validatePhoneNumber(phoneInput.value)) {
                e.preventDefault();
                alert('Please enter a valid phone number.');
                phoneInput.focus();
            } else if (messageInput.value.trim() === '') {
                e.preventDefault();
                alert('Message cannot be empty.');
                messageInput.focus();
            }
        });
    }

    const publicSmsForm = document.getElementById('publicSmsForm');
    if (publicSmsForm) {
        publicSmsForm.addEventListener('submit', function (e) {
            const messageInput = document.getElementById('public_message');

            if (messageInput.value.trim() === '') {
                e.preventDefault();
                alert('Message cannot be empty.');
                messageInput.focus();
            }
        });
    }

    const confirmButtons = document.querySelectorAll('.confirm-action');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to proceed?')) {
                e.preventDefault();
            }
        });
    });

    const programDropdown = document.getElementById('programFilter');
    if (programDropdown) {
        programDropdown.addEventListener('change', function () {
            const selectedProgram = programDropdown.value;
            filterStudentsByProgram(selectedProgram);
        });
    }
});

function validatePhoneNumber(phone) {
    const phoneRegex = /^[0-9]{10,12}$/;
    return phoneRegex.test(phone);
}

function filterStudentsByProgram(programId) {
    const studentRows = document.querySelectorAll('.student-row');
    studentRows.forEach(row => {
        if (programId === 'all' || row.dataset.programId === programId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const recipientType = document.getElementById('recipientType');
    const programSelector = document.getElementById('programSelector');
    const studentSelector = document.getElementById('studentSelector');
    const programDropdown = document.getElementById('programDropdown');

    // Load programs
    function loadPrograms() {
        fetch('fetch_programs.php')
            .then(response => response.json())
            .then(programs => {
                programs.forEach(program => {
                    const option = document.createElement('option');
                    option.value = program.program_id;
                    option.textContent = program.program_name;
                    programDropdown.appendChild(option);
                });
            });
    }

    recipientType.addEventListener('change', () => {
        if (recipientType.value === 'public') {
            programSelector.style.display = 'block';
            studentSelector.style.display = 'none';
        } else {
            programSelector.style.display = 'none';
            studentSelector.style.display = 'block';
        }
    });

    loadPrograms();
});

document.getElementById('addProgramForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const programName = document.getElementById('programName').value.trim();
    const programMessage = document.getElementById('programMessage');

    if (!programName) {
        programMessage.innerHTML = '<div class="alert alert-danger">Program name cannot be empty.</div>';
        return;
    }

    fetch('../admin/add_program.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ programName })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                programMessage.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                document.getElementById('programName').value = '';
                updateProgramDropdown();
            } else {    
                programMessage.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            }
        })
        .catch(error => {
            programMessage.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        });
});

function updateProgramDropdown() {
    const programDropdown = document.getElementById('programDropdown');
    programDropdown.innerHTML = '<option value="" disabled selected>Select your program</option>';

    fetch('../admin/fetch_programs.php')
        .then(response => response.json())
        .then(programs => {
            programs.forEach(program => {
                const option = document.createElement('option');
                option.value = program.program_id;
                option.textContent = program.program_name; 
                programDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error updating dropdown:', error));
}

