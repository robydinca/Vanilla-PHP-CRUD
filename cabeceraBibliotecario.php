<?php
$cabeceraBibliotecario = <<<EX
<header>
  <img src="logo.png" alt="logo">
  <h1>Panel de control biblioteca</h1>
  <a href="logout.php">
    salir
  </a>
  <a href="gestionPerfil.php">
    <img src="profile.png" alt="profile">
  </a>
</header>
<nav>
  <ul>
      <li><a href="index.php">Inicio</a></li>
      <li><a href="listarAutores.php">Listar Autores</a></li>
      <li><a href="listarLibros.php">Listar Libros</a></li>
      <li><a href="insertarLibro.php">Insertar Libro</a></li>
      <li><a href="insertarAutor.php">Insertar Autor</a></li>
      <li><a href="buscarLibro.php">Buscar libro</a></li>
  </ul>
</nav>
EX;
?>