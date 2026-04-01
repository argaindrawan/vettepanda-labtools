const apiUrl = 'api.php';
const inventoryTable = document.querySelector('#inventoryTable tbody');
const form = document.querySelector('#inventoryForm');
const itemIdField = document.getElementById('itemId');
const itemNameField = document.getElementById('itemName');
const categoryField = document.getElementById('category');
const quantityField = document.getElementById('quantity');
const unitField = document.getElementById('unit');
const locationField = document.getElementById('location');
const notesField = document.getElementById('notes');
const formTitle = document.getElementById('formTitle');
const submitBtn = document.getElementById('submitBtn');
const clearBtn = document.getElementById('clearBtn');
const searchField = document.getElementById('search');
const filterCategory = document.getElementById('filterCategory');
const refreshBtn = document.getElementById('refreshBtn');

async function fetchItems() {
  const q = encodeURIComponent(searchField.value.trim());
  const cat = encodeURIComponent(filterCategory.value);
  const res = await fetch(`${apiUrl}?action=list&search=${q}&category=${cat}`);
  const data = await res.json();
  renderItems(data.items || []);
}

function renderItems(items) {
  inventoryTable.innerHTML = '';
  if (items.length === 0) {
    inventoryTable.innerHTML = '<tr><td colspan="8" style="text-align:center;">No records found</td></tr>';
    return;
  }

  items.forEach(item => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${escapeHtml(item.item_name)}</td>
      <td>${escapeHtml(item.category)}</td>
      <td>${item.quantity}</td>
      <td>${escapeHtml(item.unit)}</td>
      <td>${escapeHtml(item.location)}</td>
      <td>${item.last_updated}</td>
      <td>${escapeHtml(item.notes)}</td>
      <td>
        <button class="actionButton editBtn" data-id="${item.id}">Edit</button>
        <button class="actionButton deleteBtn" data-id="${item.id}">Delete</button>
      </td>`;

    inventoryTable.appendChild(tr);
  });

  inventoryTable.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => loadItemToForm(btn.dataset.id));
  });
  inventoryTable.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', () => deleteItem(btn.dataset.id));
  });
}

function escapeHtml(text) {
  if (!text) return '';
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

async function loadItemToForm(id) {
  const res = await fetch(`${apiUrl}?action=list`);
  const data = await res.json();
  const item = (data.items || []).find(i => i.id == id);
  if (!item) return;

  itemIdField.value = item.id;
  itemNameField.value = item.item_name;
  categoryField.value = item.category;
  quantityField.value = item.quantity;
  unitField.value = item.unit;
  locationField.value = item.location;
  notesField.value = item.notes;
  formTitle.textContent = 'Edit Item';
  submitBtn.textContent = 'Update';
}

function clearForm() {
  itemIdField.value = '';
  form.reset();
  formTitle.textContent = 'Add Item';
  submitBtn.textContent = 'Save';
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  const payload = {
    item_name: itemNameField.value.trim(),
    category: categoryField.value,
    quantity: Number(quantityField.value),
    unit: unitField.value.trim(),
    location: locationField.value.trim(),
    notes: notesField.value.trim(),
  };

  const id = itemIdField.value;
  const action = id ? 'update&id=' + encodeURIComponent(id) : 'add';
  const method = id ? 'PUT' : 'POST';

  const res = await fetch(`${apiUrl}?action=${action}`, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  });

  const data = await res.json();
  if (data.error) {
    alert('Error: ' + data.error);
    return;
  }

  clearForm();
  fetchItems();
});

clearBtn.addEventListener('click', clearForm);
searchField.addEventListener('input', debounce(fetchItems, 250));
filterCategory.addEventListener('change', fetchItems);
refreshBtn.addEventListener('click', fetchItems);

async function deleteItem(id) {
  if (!confirm('Delete item permanently?')) return;
  const res = await fetch(`${apiUrl}?action=delete&id=${encodeURIComponent(id)}`, { method: 'DELETE' });
  const data = await res.json();
  if (data.error) { alert('Error: ' + data.error); return; }
  fetchItems();
}

function debounce(fn, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

fetchItems();
