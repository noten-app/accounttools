var sectionIndex = 0;
var sections = document.querySelectorAll("section");

// Calculate the height of each section and the total scroll height
var sectionHeight = window.innerHeight;
var totalScrollHeight = sectionHeight * sections.length;

// Listen for the scroll event
window.addEventListener("wheel", function(event) {
    // Determine the direction of the scroll
    var delta = event.deltaY;
    if (delta > 0) {
        // Scrolling down
        sectionIndex++;
    } else if (delta < 0) {
        // Scrolling up
        sectionIndex--;
    }

    // Ensure that sectionIndex stays within bounds
    sectionIndex = Math.max(0, Math.min(sectionIndex, sections.length - 1));

    // Scroll to the selected section
    sections[sectionIndex].scrollIntoView({ behavior: "smooth" });

    // Update the scrollbar position
    var scrollPosition = (sectionHeight * sectionIndex) / totalScrollHeight;
    document.body.scrollTop = scrollPosition * totalScrollHeight;
});