<nav class="main-navbar">
  <div class="logo">🙏 Prayer System</div>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="prayer_sutras.php">Sutras</a></li>
    <li><a href="prayer_melodies.php">Melodies</a></li>
    <li><a href="upload.php">Upload</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
  <div class="search-bar">
    <form action="search.php" method="GET">
      <input type="text" name="q" placeholder="Search prayers..." required>
      <button type="submit">🔍 Search</button>
    </form>
  </div>
</nav>


<style>
 .main-navbar {
  background: #2c3e50;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between; /* space between logo + right side */
  align-items: center;
  flex-wrap: wrap;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.main-navbar .logo {
  color: #ffd700;
  font-weight: bold;
  font-size: 22px;
  flex: 1; /* keeps logo aligned left */
}

.main-navbar ul {
  list-style: none;
  display: flex;
  justify-content: center; /* centers menu items */
  flex: 2; /* menu takes middle space */
  gap: 25px;
  margin: 0;
  padding: 0;
}

.main-navbar li {
  margin: 0;
}

.main-navbar a {
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  padding: 6px 10px;
  border-radius: 4px;
  transition: background 0.3s ease, color 0.3s ease;
}

.main-navbar a:hover,
.main-navbar a.active {
  background: #ffd700;
  color: #2c3e50;
}

.search-bar {
  flex: 1; /* aligns search bar to right */
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.search-bar input {
  padding: 8px 12px;
  border-radius: 20px;
  border: 1px solid #ccc;
  outline: none;
}

.search-bar button {
  padding: 8px 14px;
  background: #4a90e2;
  color: #fff;
  border: none;
  border-radius: 20px;
  cursor: pointer;
}

.search-bar button:hover {
  background: #357ab8;
}

</style>
