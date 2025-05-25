document.addEventListener('DOMContentLoaded', () => {
    const booksContainer = document.getElementById('moodBooks');
    const sentenceContainer = document.getElementById('moodSentence');
    const loadingContainer = document.getElementById('moodLoading');
    const heading = document.querySelector('.mood-selection h2');

    const MOOD_STORAGE_KEY = 'selectedMood';
    const BOOKS_STORAGE_KEY = 'recommendedBooks';
    const SENTENCE_STORAGE_KEY = 'moodSentence';

    // Restore previous state if exists
    const storedBooks = JSON.parse(localStorage.getItem(BOOKS_STORAGE_KEY));
    const storedSentence = localStorage.getItem(SENTENCE_STORAGE_KEY);
    const storedMood = localStorage.getItem(MOOD_STORAGE_KEY);

    if (storedBooks && storedBooks.length && storedSentence && storedMood) {
        heading.textContent = "Did your mood change or something else?";

        highlightMoodButton(storedMood);

        const moodSentence = document.createElement('h2');
        moodSentence.textContent = storedSentence;
        moodSentence.style.textAlign = 'center';
        moodSentence.style.margin = '60px 0 10px 0';
        sentenceContainer.appendChild(moodSentence);

        storedBooks.forEach(book => {
            booksContainer.innerHTML += createBookCard(book);
        });
    }

    document.querySelectorAll('.mood-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const mood = this.getAttribute('data-mood');

            // Clear containers
            booksContainer.innerHTML = '';
            sentenceContainer.innerHTML = '';

            // Show loader
            loadingContainer.style.display = 'block';

            fetch('aiMoodRecommender.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mood })
            })
            .then(res => res.json())
            .then(data => {
                loadingContainer.style.display = 'none';

                if (data.error) {
                    sentenceContainer.innerHTML = `<p class="error-message" style="text-align:center;">${data.error}</p>`;
                    return;
                }

                heading.textContent = "Did your mood change?";
                highlightMoodButton(mood);

                const moodSentence = document.createElement('h2');
                moodSentence.textContent = data.sentence || '';
                moodSentence.style.textAlign = 'center';
                moodSentence.style.margin = '60px 0 10px 0';
                sentenceContainer.appendChild(moodSentence);

                booksContainer.innerHTML = ''; // Clear again just in case
                data.books.forEach(book => {
                    booksContainer.innerHTML += createBookCard(book);
                });

                // Save to localStorage
                localStorage.setItem(MOOD_STORAGE_KEY, mood);
                localStorage.setItem(BOOKS_STORAGE_KEY, JSON.stringify(data.books));
                localStorage.setItem(SENTENCE_STORAGE_KEY, data.sentence || '');
            })
            .catch(() => {
                loadingContainer.style.display = 'none';
                sentenceContainer.innerHTML = `<p class="error-message" style="text-align:center;">Something went wrong. Try again later.</p>`;
            });
        });
    });

    function createBookCard(book) {
        return `
        <div class="book-card">
            <a href="signinBook.php?book_id=${book.book_id}" class="book-card-link">
                <img src="images/coverimages/${book.cover_image_url}" alt="${book.title}">
            </a>
            <div class="title-author-price">
                <div class="title-author">
                    <h1 title="${book.title}">${book.title}</h1>
                    <h2>${book.author_name}</h2>
                </div>
                <h1 class="price">€ ${parseFloat(book.price).toFixed(2)}</h1>
            </div>
            <p class="book-category">${book.category_name || 'Uncategorized'}</p>
        </div>`;
    }

    function highlightMoodButton(mood) {
        document.querySelectorAll('.mood-btn').forEach(btn => {
            if (btn.getAttribute('data-mood') === mood) {
                btn.classList.add('active-mood');
            } else {
                btn.classList.remove('active-mood');
            }
        });
    }
});
