window.onload = function() {
    fetchAuthors();
    fetchBooks();
  };

function fetchAuthors() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost:8080?table=auteurs', true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var authors = JSON.parse(xhr.responseText);
        var authorSelect = document.getElementById('author');
        authors.forEach(function(author) {
          var option = document.createElement('option');
          option.value = author.id_auteur;
          option.textContent = author.nom + " " + author.prénom;
          authorSelect.appendChild(option);
        });
      }
    };
    xhr.send();
  }
  
function fetchBooks() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost:8080?table=livres', true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var authors = JSON.parse(xhr.responseText);
        var authorSelect = document.getElementById('book');
        authors.forEach(function(author) {
          var option = document.createElement('option');
          option.value = author.code_livre;
          option.textContent = author.titre;
          authorSelect.appendChild(option);
        });
      }
    };
    xhr.send();
  }
function BookByAuthor() {
xhr= new XMLHttpRequest();
var AuteurName = document.getElementById("AuteurName").value;
var url="http://localhost:8080?table=Auteurs_Livres&name="+AuteurName;
xhr.onreadystatechange=function(){
if(xhr.readyState==4 && xhr.status==200) {
    const myBooks1= this.responseText;
    document.getElementById('BookByAuthorRoot').innerHTML = "";
    CreatTable(myBooks1,'BookByAuthorRoot',"BookByAuthorTable")
};
};
xhr.open("GET",url,true);
xhr.send();
}
function ListALlBooks() {
    xhr= new XMLHttpRequest();
    var url="http://localhost:8080?table=livres";
    xhr.onreadystatechange=function(){
    if(xhr.readyState==4 && xhr.status==200) {
        const response= this.responseText;
        document.getElementById('BookByAuthorRoot').innerHTML = "";
        CreatTable(response,'Books',"BooksTable")
    };
    };
    xhr.open("GET",url,true);
    xhr.send();
    }
function ListALlAuthors() {
    xhr= new XMLHttpRequest();
    var url="http://localhost:8080?table=auteurs";
    xhr.onreadystatechange=function(){
    if(xhr.readyState==4 && xhr.status==200) {
        const response= this.responseText;
        document.getElementById('BookByAuthorRoot').innerHTML = "";
        CreatTable(response,'Authors',"AuthorsTable")
    };
    };
    xhr.open("GET",url,true);
    xhr.send();
    }
function alert(elm) {
    if (confirm('Are you sure you want to delete this module from the database?')) {
        var x=$(elm).closest("tr").find("td:first-child").text();
        const row = elm.parentNode.parentNode.parentNode.parentNode; // Get the row

        const tableName = row.getAttribute('id');

        dell(x,tableName)
        } 
    
    
}

function AddBook() {
xhr= new XMLHttpRequest();
var titre = document.getElementById("titre").value;
var anneeedition = document.getElementById("anneeedition").value;

var url="http://localhost:8080?table=livres&titre="+titre+"&annee_edition="+anneeedition;
xhr.onreadystatechange=function(){
if(xhr.readyState==4 && xhr.status==200) {
    const myBooks4= this.responseText;
    fetchBooks();
    if(xhr.responseText.length<10)alert("la ligne n'existe pas");
    
};
};
xhr.open("POST",url,true);
xhr.send();
}
function AddAuthor() {
    xhr= new XMLHttpRequest();
    var nom = document.getElementById("nom").value;
    var prenom = document.getElementById("prenom").value;
    
    var url="http://localhost:8080?table=auteurs&nom="+nom+"&prenom="+prenom;
    xhr.onreadystatechange=function(){
    if(xhr.readyState==4 && xhr.status==200) {
        fetchAuthors();
        const myBooks4= this.responseText;
        if(xhr.responseText.length<10)alert("la ligne n'existe pas");
        
    };
    };
    xhr.open("POST",url,true);
    xhr.send();
    }
function dell(x,name){
xhr= new XMLHttpRequest();
switch(name){
    case "BookByAuthorTable":
        var url= "http://localhost:8080?table=Livres&id="+x;
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200) 
            {
                BookByAuthor();
               
            }
            };
            xhr.open("DELETE",url,true);
            xhr.send();
        break;
    case "BooksTable":
        var url= "http://localhost:8080?table=Livres&id="+x;
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200) 
            {
                ListALlBooks();
               
            }
            };
            xhr.open("DELETE",url,true);
            xhr.send();
        break;
    case "AuthorsTable":
        var url= "http://localhost:8080?table=Livres&id="+x;
        xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200) 
            {
                ListALlAuthors();
               
            }
            };
            xhr.open("DELETE",url,true);
            xhr.send();
        break;


}

}

function AffectBook() {
    xhr= new XMLHttpRequest();
    var author = document.getElementById("author").value;
    var book = document.getElementById("book").value;
    
    var url="http://localhost:8080?table=Auteurs_Livres&id_auteur="+author+"&code_livre="+book;
    xhr.onreadystatechange=function(){
    if(xhr.readyState==4 && xhr.status==200) {
        const myBooks4= this.responseText;
        if(xhr.responseText.length<10)alert("la ligne n'existe pas");
        
    };
    };
    xhr.open("POST",url,true);
    xhr.send();
    }

function UpdateBook() {
    xhr= new XMLHttpRequest();
    var id = document.getElementById("id").value;
    var titre = document.getElementById("titre").value;
    var anneeedition = document.getElementById("anneeedition").value;
    
    var url="http://localhost:8080?table=livres&id="+id+"titre="+titre+"&annee_edition="+anneeedition;
    xhr.onreadystatechange=function(){
    if(xhr.readyState==4 && xhr.status==200) {
        const myBooks4= this.responseText;
    fetchBooks();
        if(xhr.responseText.length<10)alert("la ligne n'existe pas");
        
    };
    };
    xhr.open("PUT",url,true);
    xhr.send();
    }
function UpdateAuthor() {
    xhr= new XMLHttpRequest();
    var id = document.getElementById("id").value;
    var nom = document.getElementById("nom").value;
    var prenom = document.getElementById("prenom").value;
    
    var url="http://localhost:8080?table=auteurs&id="+id+"nom="+nom+"&prenom="+prenom;
    xhr.onreadystatechange=function(){
    if(xhr.readyState==4 && xhr.status==200) {
        fetchAuthors();
        const myBooks4= this.responseText;
        if(xhr.responseText.length<10)alert("la ligne n'existe pas");
        
    };
    };
    xhr.open("PUT",url,true);
    xhr.send();
    }
function CreatTable(data,element,tableName){
    const parsed = JSON.parse(data);
        const keys = Object.keys(parsed[0]);
        
        // Build the table header
        const header = `<thead><tr>` + keys
            .map(key => `<th>${key}</th>`)
            .join('') + `</thead></tr>`;
            
        // Build the table body
        const body = `<tbody>` + parsed
            .map(row => `<tr>${Object.values(row)
            .map(cell => `<td>${cell}</td>`)
            .join('')} 
            
            <td><button class="button" onclick="alert(this);">Supprimer</button></td>
                </tr> `
            ).join('');
            
        // Build the final table
        const table = `
        <table id=${tableName}>
            ${header}
            ${body}
        </table>
`                        ;

document.getElementById(element).innerHTML = table;
    
}
