const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function confirmDelete(deleteUrl) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#696cff',
        // confirmButtonColor: '#d33',
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#3085d6',
        cancelButtonColor: '#696cff',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a hidden form dynamically and submit it
            let form = document.createElement('form');
            form.action = deleteUrl;
            form.method = 'POST';
            form.style.display = 'none';

            // Add CSRF and DELETE method fields
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;

            document.body.appendChild(form);
            form.submit();
        }
    });
}
