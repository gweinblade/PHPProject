<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
// Database connection parameters
$servername = "localhost";
$username = "admin";
$password = "admin";
$database = "library_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Handle GET requests
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Get all books
    if ($_GET["table"] === "livres") {
        $sql = "SELECT * FROM livres";
        $result = $conn->query($sql);
       
        $books = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        echo json_encode($books);
    }
    

    // Get all authors
    elseif ($_GET["table"] === "auteurs") {
        $sql = "SELECT * FROM Auteurs";
        $result = $conn->query($sql);
        $authors = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $authors[] = $row;
            }
        }
        echo json_encode($authors);
    }

    // Get book by ID
    elseif ($_GET["table"] === "livres" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT * FROM Livres WHERE code_livre = $id";
        $result = $conn->query($sql);
        $book = $result->fetch_assoc();
        echo json_encode($book);
    }

    // Get author by ID
    elseif ($_GET["table"] === "auteurs" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT * FROM Auteurs WHERE id_auteur = $id";
        $result = $conn->query($sql);
        $author = $result->fetch_assoc();
        echo json_encode($author);
    }
    
    // Get books by Author id
    elseif ($_GET["table"] === "Auteurs_Livres" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT Livres.* FROM Livres JOIN Auteurs_Livres ON Livres.code_livre = Auteurs_Livres.code_livre WHERE Auteurs_Livres.id_auteur = $id";
        $result = $conn->query($sql);
        $books = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        echo json_encode($books);
    }
    // Get books by Author name/prenom
    elseif ($_GET["table"] === "Auteurs_Livres" && isset($_GET["name"])) {
        $name = $_GET["name"];
        $sql = "SELECT Livres.code_livre as 'Book ID',Auteurs.id_auteur as 'Auteur ID', Livres.titre as Titre, Livres.annee_edition as 'Année d''édition',Concat(Auteurs.nom,' ',Auteurs.prénom) as 'Auteur'  FROM Livres,Auteurs_Livres,Auteurs  WHERE Livres.code_livre = Auteurs_Livres.code_livre  and Auteurs_Livres.id_auteur = Auteurs.id_auteur       and ( Auteurs.nom like '%$name%' or Auteurs.prénom like '%$name%' )";     
        $result = $conn->query($sql);
        $books = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        echo json_encode($books);
    }
}

// Handle POST requests
elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Add a new book
    if ($_GET["table"] === "livres") {
        $data = json_decode(file_get_contents("php://input"), true);
        $titre = $data["titre"];
        $annee_edition = (int)$data["annee_edition"];
        $sql = "INSERT INTO Livres (titre, annee_edition) VALUES ( '$titre', $annee_edition );";
        if ($conn->query($sql) === TRUE) {
            echo "Book added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Add a new author
    elseif ($_GET["table"] === "auteurs") {
        $data = json_decode(file_get_contents("php://input"), true);
        $nom = $data["nom"];
        $prenom = $data["prenom"];
        $sql = "INSERT INTO Auteurs (nom, prénom) VALUES ('$nom', '$prenom')";
        if ($conn->query($sql) === TRUE) {
            echo "Author added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    elseif ($_GET["table"] === "Auteurs_Livres") {
        
        $id_auteur = (int)$_GET["id_auteur"];
        $code_livre = (int)$_GET["code_livre"];
        // Check if author ID exists
        $check_author_sql = "SELECT * FROM Auteurs WHERE id_auteur = $id_auteur";
        $author_result = $conn->query($check_author_sql);
        if ($author_result->num_rows == 0) {
            echo "Error: Author with ID $id_auteur does not exist.";
            exit;
        }
        $sql = "INSERT INTO Auteurs_Livres (id_auteur, code_livre) VALUES ($id_auteur, $code_livre)";
        if ($conn->query($sql) === TRUE) {
            echo "Author assigned to book successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle PUT requests
elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {
    parse_str(file_get_contents("php://input"), $data);

    // Update book by ID
    if ($_GET["table"] === "livres" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $titre = $data["titre"];
        $annee_edition = $data["annee_edition"];
        $sql = "UPDATE Livres SET titre = '$titre', annee_edition = '$annee_edition' WHERE code_livre = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Book updated successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Update author by ID
    elseif ($_GET["table"] === "auteurs" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $nom = $data["nom"];
        $prenom = $data["prenom"];
        $sql = "UPDATE Auteurs SET nom = '$nom', prénom = '$prenom' WHERE id_auteur = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Author updated successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle DELETE requests
elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Delete book by ID
    if ($_GET["table"] === "livres" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "DELETE FROM Livres WHERE code_livre = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Book deleted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Delete author by ID
    elseif ($_GET["table"] === "auteurs" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "DELETE FROM Auteurs WHERE id_auteur = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Author deleted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    elseif ($_GET["table"] === "Auteurs_Livres" && isset($_GET["id_auteur"]) && isset($_GET["code_livre"])) {
        $id_auteur = (int)$_GET["id_auteur"];
        $code_livre = (int)$_GET["code_livre"];
        $sql = "DELETE FROM Auteurs_Livres WHERE id_auteur = $id_auteur AND code_livre = $code_livre";
        if ($conn->query($sql) === TRUE) {
            echo "Author removed from book successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>