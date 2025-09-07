document.getElementById("bookingForm").addEventListener("submit", function(e) {
    const room = this.room.value.trim();
    const checkIn = this.check_in.value.trim();
    const checkOut = this.check_out.value.trim();

    // যদি সব খালি থাকে তাহলে index এ থাকবে
    if (!room && !checkIn && !checkOut) {
        e.preventDefault(); // form submit হবে না
        alert("Please select at least one option before searching.");
    }
});
