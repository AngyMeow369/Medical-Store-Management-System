<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sold Products (History)</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #17a2b8;
      --primary-light: rgba(26, 173, 195, 0.9);
      --accent: #10B981;
      --bg: #F9FAFB;
      --surface: #FFFFFF;
      --text: #374151;
      --text-light: #6B7280;
      --shadow: rgba(0, 0, 0, 0.06);
    }
    body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); }
    a { text-decoration: none; color: inherit; }
    .topnav { height: 60px; background: var(--surface); display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; box-shadow: 0 2px 8px var(--shadow); position: sticky; top: 0; z-index: 1000; }
    .topnav a { color: var(--primary); font-weight: 600; }
    .menu-icon { font-size: 1.8rem; cursor: pointer; color: var(--primary); }
    .sidenav { height: 100%; width: 0; position: fixed; z-index: 1200; top: 0; left: 0; background: var(--surface); overflow-x: hidden; transition: width 0.4s; box-shadow: 2px 0 12px var(--shadow); padding-top: 20px; }
    .sidenav.open { width: 220px; }
    .sidenav h2 { text-align: center; margin: 0.5rem; font-size: 1.2rem; color: var(--primary); }
    .sidenav a, .sidenav button { display: flex; align-items: center; gap: 10px; padding: 0.6rem 1.5rem; font-size: 0.95rem; color: var(--text); background: none; border: none; text-align: left; width: 100%; cursor: pointer; }
    .sidenav a:hover, .sidenav button:hover { background: var(--primary-light); color: var(--surface); border-radius: 0 20px 20px 0; }
    .dropdown-container { display: none; background: #EFF6FF; }
    .dropdown-container a { padding-left: 2.5rem; }
    .dropdown-btn:after { content: " \25BC"; margin-left: auto; }
    .dropdown-btn.active:after { content: " \25B2"; }
    .content { padding: 1rem; margin-left: 220px; transition: margin-left 0.4s; }
    .shifted { margin-left: 0; }
    .sort-container { text-align: right; margin-bottom: 1rem; }
    select { padding: 5px 10px; font-size: 0.85rem; border: 1px solid #CBD5E1; border-radius: 6px; background: var(--surface); color: var(--text); box-shadow: 0 2px 4px var(--shadow); cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: var(--surface); font-size: 0.85rem; box-shadow: 0 4px 12px var(--shadow); border-radius: 8px; overflow: hidden; }
    th, td { padding: 0.6rem; text-align: center; border-bottom: 1px solid #E5E7EB; }
    th { background: var(--primary); color: white; text-transform: uppercase; }
    tr:hover { background-color: #F1F5F9; }
    h2 { text-align: center; color: var(--primary); margin: 2rem 0 1rem; }
  </style>
</head>
<body>
<div class="topnav">
  <span class="menu-icon" onclick="toggleNav()"><i class="fas fa-bars"></i></span>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<div id="mySidenav" class="sidenav open">
  <h2><i class="fas fa-clinic-medical"></i> Medical Store</h2>
  <a href="javascript:void(0)" onclick="toggleNav()"><i class="fas fa-times"></i> Close</a>
  <a href="adminmainpage.php"><i class="fas fa-home"></i> Dashboard</a>
  <a href="salesitems-view.php"><i class="fas fa-boxes"></i> Sold Products</a>
</div>
<div class="content" id="mainContent">
  <h2>Sold Products from Order History</h2>
  <div class="sort-container">
    <form method="GET">
      <label for="sort">Sort:</label>
      <select name="sort" onchange="this.form.submit()">
        <option value="desc" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'desc') ? 'selected' : '' ?>>Most Sold</option>
        <option value="asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'selected' : '' ?>>Least Sold</option>
        <option value="med" <?= (isset($_GET['sort']) && $_GET['sort'] == 'med') ? 'selected' : '' ?>>Medicine ID</option>
      </select>
    </form>
  </div>
  <table>
    <thead>
      <tr>
        <th>Medicine ID</th>
        <th>Medicine Name</th>
        <th>Quantity Sold</th>
        <th>Total Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      include "config.php";
      $sort = $_GET['sort'] ?? 'desc';
      $orderBy = "SUM(oi.quantity) DESC";
      if ($sort === 'asc') $orderBy = "SUM(oi.quantity) ASC";
      elseif ($sort === 'med') $orderBy = "oi.med_id ASC";

      $sql = "SELECT oi.med_id, m.med_name, SUM(oi.quantity) AS qty_sold, SUM(oi.total_price) AS total
              FROM order_items_history oi
              JOIN meds m ON oi.med_id = m.med_id
              GROUP BY oi.med_id
              ORDER BY $orderBy";

      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td>{$row['med_id']}</td>
            <td>{$row['med_name']}</td>
            <td>{$row['qty_sold']}</td>
            <td>₹{$row['total']}</td>
          </tr>";
        }
      }
      $conn->close();
      ?>
    </tbody>
  </table>
</div>
<script>
function toggleNav() {
  const sidenav = document.getElementById("mySidenav");
  const content = document.getElementById("mainContent");
  sidenav.classList.toggle("open");
  content.classList.toggle("shifted");
}
const dropdowns = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdowns.length; i++) {
  dropdowns[i].addEventListener("click", function () {
    this.classList.toggle("active");
    const dropdownContent = this.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
  });
}
</script>
</body>
</html>
