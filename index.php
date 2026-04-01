<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Research Inventory Manager</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>Research Inventory Manager</h1>
    <p>Arga Dwi Indrawan Labs</p>

    <div class="toolbar">
      <input id="search" placeholder="Search by name or location..." />
      <select id="filterCategory">
        <option value="">All Categories</option>
        <option value="equipment">Equipment</option>
        <option value="material">Material</option>
      </select>
      <button id="refreshBtn">Refresh</button>
    </div>

    <table id="inventoryTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Unit</th>
          <th>Location</th>
          <th>Updated</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <h2 id="formTitle">Add Item</h2>
    <form id="inventoryForm">
      <input type="hidden" id="itemId" />
      <label>Item name<input type="text" id="itemName" required /></label>
      <label>Category
        <select id="category" required>
          <option value="equipment">Equipment</option>
          <option value="material">Material</option>
        </select>
      </label>
      <label>Quantity<input type="number" id="quantity" min="0" required /></label>
      <label>Unit<input type="text" id="unit" required /></label>
      <label>Location<input type="text" id="location" /></label>
      <label>Notes<textarea id="notes" rows="2"></textarea></label>

      <div class="buttons">
        <button type="submit" id="submitBtn">Save</button>
        <button type="button" id="clearBtn">Clear</button>
      </div>
    </form>
  </div>

  <script src="app.js"></script>
</body>
</html>