document.addEventListener('DOMContentLoaded', function() {
    // Class booking functionality
    const bookButtons = document.querySelectorAll('.book-class');
    
    bookButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const classId = this.dataset.classId;
            const classCard = this.closest('.class-card');
            const spotsElement = classCard.querySelector('p:nth-of-type(3)'); // Spots remaining element
            
            // Disable button during request
            this.disabled = true;
            this.textContent = 'Processing...';
            
            try {
                const response = await fetch('api/book_class.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        class_id: classId,
                        member_id: document.body.dataset.memberId // Add member_id to body dataset
                    })
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update UI immediately
                    const spotsText = spotsElement.textContent;
                    const currentSpots = parseInt(spotsText.match(/\d+/)[0]);
                    spotsElement.textContent = spotsText.replace(/\d+/, currentSpots - 1);
                    
                    // Show success message
                    showAlert('Class booked successfully!', 'success');
                    
                    // Update button state
                    this.textContent = 'Booked!';
                    this.classList.add('booked');
                    
                    // If on dashboard, refresh the list
                    if (window.location.pathname.includes('dashboard.php')) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } 
                else if (data.status === 'waitlisted') {
                    if (confirm('Class is full. Would you like to join the waitlist?')) {
                        const waitlistResponse = await fetch('api/waitlist.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ 
                                class_id: classId,
                                member_id: document.body.dataset.memberId
                            })
                        });
                        
                        const waitlistData = await waitlistResponse.json();
                        
                        if (waitlistData.status === 'success') {
                            showAlert('Added to waitlist successfully!', 'success');
                            this.textContent = 'Waitlisted';
                            this.classList.add('waitlisted');
                        } else {
                            throw new Error(waitlistData.error || 'Failed to join waitlist');
                        }
                    }
                } 
                else {
                    throw new Error(data.error || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Booking error:', error);
                showAlert(error.message, 'error');
                this.textContent = 'Book Now';
                this.disabled = false;
            }
        });
    });

    // Alert notification function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${type}`;
        alertDiv.textContent = message;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.classList.add('fade-out');
            setTimeout(() => alertDiv.remove(), 500);
        }, 3000);
    }

    // Calendar integration (example with FullCalendar)
    const calendarEl = document.getElementById('class-calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: 'api/get_classes.php',
            eventClick: function(info) {
                const classId = info.event.extendedProps.classId;
                const bookBtn = document.querySelector(`.book-class[data-class-id="${classId}"]`);
                if (bookBtn) {
                    bookBtn.click();
                }
            }
        });
        calendar.render();
    }
});

