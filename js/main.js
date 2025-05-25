document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const navList = document.querySelector('ul');
    // const sidebartoggle = document.querySelector('.sidebar');
    // const sidebar = document.querySelector('#sidebar');
    const header = document.querySelector('header');

    menuToggle.addEventListener('click', function () {
        navList.classList.toggle('show');
    });
    // sidebartoggle.addEventListener('click', function () {
    //   sidebar.classList.toggle('showw');
    // });

    window.addEventListener("scroll", function() {
        const scrollPosition = window.scrollY;
        if (scrollPosition > 98.188) {
          header.classList.add("hidden");
        } else {
          header.classList.remove("hidden");
        }
      });
    
      const screenHeight = window.innerHeight;
      const hoverHeight = screenHeight * 0.1;
    
      window.addEventListener("mousemove", function(event) {
        if (event.clientY < hoverHeight) {
          header.classList.remove("hidden");
        }
      });
});



const sections = document.querySelectorAll('.books-contain');
let currentSectionIndex = 0;
let isAnimating = false;

function showNextSection() {
    if (isAnimating) return;

    isAnimating = true;

    const currentSection = sections[currentSectionIndex];
    currentSection.style.opacity = '0';  // Start fading out the current section

    // Wait for the fade-out to complete before hiding the section and showing the next one
    setTimeout(() => {
        currentSection.classList.remove('activee');
        currentSection.style.display = 'none'; // Hide the current section after fade-out

        // Move to the next section
        currentSectionIndex = (currentSectionIndex + 1) % sections.length;

        const nextSection = sections[currentSectionIndex];
        nextSection.style.display = 'flex'; // Show the next section
        setTimeout(() => {
            nextSection.classList.add('activee'); // Start fading in the next section
            nextSection.style.opacity = '1';
            isAnimating = false;
        }, 50);  // Add a small delay to ensure the display change is processed before fading in

    }, 600);  // The timeout should match the opacity transition duration (1s)
}

// Change sections every 10 seconds
setInterval(showNextSection, 10000);

















document.addEventListener("DOMContentLoaded", function() {
  let counter = 1;
  const totalSlides = 4; // Adjust this value based on the total number of slides
  const slides = document.querySelectorAll("#section6 .slide");
  const manualBtns = document.querySelectorAll(".manual-btn");

  function showSlide(index) {
      slides.forEach((slide, idx) => {
          slide.classList.remove("active", "previous");
          if (idx === index - 1) {
              slide.classList.add("active");
          } else if (idx === (index === 1 ? totalSlides - 1 : index - 2)) {
              slide.classList.add("previous");
          }
      });

      manualBtns.forEach((btn, idx) => {
          btn.classList.remove("active");
          if (idx === index - 1) {
              btn.classList.add("active");
          }
      });
  }

  function autoSlide() {
      counter++;
      if (counter > totalSlides) {
          counter = 1;
      }
      showSlide(counter);
  }

  setInterval(autoSlide, 8000); // Change slide every 5 seconds

  // Manual navigation
  manualBtns.forEach((btn, idx) => {
      btn.addEventListener("click", function() {
          counter = idx + 1;
          showSlide(counter);
      });
  });

  // Initial slide setup
  showSlide(counter);
});






// document.querySelectorAll('.learnMoreLink').forEach(link => {
//     link.addEventListener('click', function(e) {
//         e.preventDefault();
//         document.querySelector('#section2').scrollIntoView({ 
//             behavior: 'smooth' 
//         });
//     });
// });



document.addEventListener('DOMContentLoaded', function() {
    var newBooksButtons = document.querySelectorAll('.newBooksButton');

    newBooksButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Open the books.php page in a new tab and wait for it to load
            const newTab = window.open('books.php', '_blank');

            // Add a listener to check when the new tab is loaded
            newTab.addEventListener('load', function() {
                // Use the new tab's document to find and click the "New Books" link
                const newBooksLink = newTab.document.querySelector('a[data-genre="new-books"]');
                if (newBooksLink) {
                    newBooksLink.click();
                }
            });
        });
    });    

    // Select all buttons with the specified href
    var buttons = document.querySelectorAll('a[href="books.php"], .ulIndex a[href="books.php"]');

    // Loop through each button and add an event listener
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Open the books.php page in a new tab and wait for it to load
            const newTab = window.open('books.php', '_blank');

            // Add a listener to check when the new tab is loaded
            newTab.addEventListener('load', function() {
                // Use the new tab's document to find and click the "All Books" link
                const allBooksLink = newTab.document.querySelector('a[data-genre="all-books"]');
                if (allBooksLink) {
                    allBooksLink.click();
                }
            });
        });
    });

    // // Select all buttons with the specified href
    // var button = document.querySelector('.form-container .submit');

    // button.addEventListener('click', function() {
    //     // Open the books.php page in a new tab and wait for it to load
    //     const newTab = window.open('signinBooks.php', '_blank');

    //     // Add a listener to check when the new tab is loaded
    //     newTab.addEventListener('load', function() {
    //         // Use the new tab's document to find and click the "All Books" link
    //         const allBooksLink = newTab.document.querySelector('a[data-genre="all-books"]');
    //         if (allBooksLink) {
    //             allBooksLink.click();
    //         }
    //      });
    // });
});







// Intersection Observer setup for observing when sections are 50% visible
const observerOptions = {
    threshold: 0.2 // 20% visibility
};

// Function to animate numbers from 0 to the target value
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = Math.floor(progress * (end - start) + start); // Update number
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Callback function to handle section visibility and number animation for section 5
const observerCallback = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible'); // Add class to show section

            // Check if section5 is visible and animate the numbers
            if (entry.target.id === 'section5') {
                document.querySelectorAll('.stat-item h1').forEach((statItem) => {
                    const targetValue = parseInt(statItem.getAttribute('data-target'));
                    animateValue(statItem, 0, targetValue, 2000); // Animate over 2 seconds
                });
            }

            observer.unobserve(entry.target); // Stop observing once it has been revealed
        }
    });
};

// Create the observer
const observer = new IntersectionObserver(observerCallback, observerOptions);

// Select all sections and observe each one
document.querySelectorAll('section:not(#section8)').forEach(section => {
    observer.observe(section);
});



document.querySelectorAll('.books-wrapper .book').forEach(function(newBook) {
    newBook.addEventListener('click', function() {
        var bookId = newBook.getAttribute('data-book-id'); // Get the book_id from data attribute
        window.location.href = `book.php?book_id=${bookId}`;
    });
});








document.getElementById('scrollToTop').addEventListener('click', function (e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
