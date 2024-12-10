function textarea() {
    const textarea = document.getElementById('textArea');
    const maxHeight = 50; // Set the maximum height in pixels

    textarea.addEventListener('input', function () {
        // Reset height to auto to recalculate
        textarea.style.height = 'auto';

        // Check if the content height exceeds the max height
        if (this.scrollHeight > maxHeight) {
            this.style.height = maxHeight + 'px'; // Set height to max height
            this.style.overflowY = 'scroll'; // Show scrollbar
        } else {
            this.style.height = this.scrollHeight + 'px'; // Adjust height to content
            this.style.overflowY = 'hidden'; // Hide scrollbar if not needed
        }
    });
}

textarea();