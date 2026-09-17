let categoryDefaultAction = '';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('categoryForm');
    if (form) {
        categoryDefaultAction = form.action;
    }
});

function editCategory(id, name, description) {
    document.getElementById('categoryFormTitle').innerText = 'Edit Kategori: ' + name;
    const form = document.getElementById('categoryForm');
    if (form) {
        if (!categoryDefaultAction) categoryDefaultAction = form.action;
        form.action = `/admin/categories/${id}`;
    }
    document.getElementById('categoryFormMethod').value = 'PUT';
    document.getElementById('categoryNameInput').value = name;
    document.getElementById('categoryDescriptionInput').value = description;
    document.getElementById('categoryFormSubmitBtn').innerText = 'Simpan';
    document.getElementById('cancelCategoryEditContainer').style.display = 'block';
}

function cancelCategoryEdit() {
    document.getElementById('categoryFormTitle').innerText = 'Tambah Kategori Baru';
    const form = document.getElementById('categoryForm');
    if (form && categoryDefaultAction) {
        form.action = categoryDefaultAction;
    }
    document.getElementById('categoryFormMethod').value = 'POST';
    document.getElementById('categoryNameInput').value = '';
    document.getElementById('categoryDescriptionInput').value = '';
    document.getElementById('categoryFormSubmitBtn').innerText = 'Tambah';
    document.getElementById('cancelCategoryEditContainer').style.display = 'none';
}