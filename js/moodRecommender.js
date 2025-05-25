document.querySelectorAll('.mood-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const mood = this.getAttribute('data-mood');

        fetch('aiMoodRecommender.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mood })
        })
        .then(res => res.json())
        .then(data => {
            const booksContainer = document.getElementById('moodBooks');
            const sentenceContainer = document.getElementById('moodSentence');

            // Clear old content
            booksContainer.innerHTML = '';
            sentenceContainer.innerHTML = '';

            if (data.error) {
                sentenceContainer.innerHTML = `<p class="error-message" style="text-align:center;">${data.error}</p>`;
                return;
            }

            // Insert mood sentence
            const moodSentence = document.createElement('h2');
            moodSentence.textContent = data.sentence || '';
            moodSentence.style.textAlign = 'center';
            moodSentence.style.margin = '60px 0 10px 0';
            sentenceContainer.appendChild(moodSentence);

            // Insert book cards
            data.books.forEach(book => {
                booksContainer.innerHTML += `
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
            });
        });
    });
});
