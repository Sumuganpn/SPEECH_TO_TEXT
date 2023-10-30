<!DOCTYPE html>
<html>
<head>
	<title>Search Bar using PHP</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f7f7f7;
			margin: 0;
			padding: 0;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}
		.container {
			width: 1200px;
			padding: 20px;
			background-color: #ffffff;
			box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			text-align: center; /* Center align the content */
		}
		form {
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		label {
			font-weight: bold;
			margin-bottom: 10px;
			color: #333;
		}
		input[type="text"] {
			width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			margin-bottom: 15px;
		}
		input[type="submit"] {
			padding: 10px 20px;
			background-color: #007bff;
			color: #fff;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s;
		}
		input[type="submit"]:hover {
			background-color: #0056b3;
		}
		h3 {
			margin-top: 20px;
			color: #333;
		}
		.error-msg {
			color: red;
			margin-top: 10px;
		}

		/* Converted text styles */
		.converted-text {
            margin-top: 30px;
        }

		.converted-text h2 {
			font-size: 20px;
			margin-bottom: 10px;
		}

		.converted-text p {
			background-color: #f0f0f0;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 4px;
		}

		.converted-text button {
			background-color: #28a745;
			color: #fff;
			border: none;
			padding: 5px 10px;
			margin-left: 10px;
			border-radius: 4px;
			cursor: pointer;
		}
		.converted-text {
            margin-top: 30px;
        }

        .converted-text h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .converted-text p {
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .converted-text button {
            background-color: #28a745;
            color: #fff;
        	border: none;
            padding: 5px 10px;
            margin-left: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
	</style>
</head>
<body>
    <div class="container">
	<form method="post" >
			<label>Search</label>
			<input type="text" name="search">
			<input type="submit" name="submit" value="Search">
		</form>

        <?php
        $con = new PDO("mysql:host=localhost;dbname=bithacks", 'root', '');

        if (isset($_POST["submit"])) {
            $str = $_POST["search"];
            $sth = $con->prepare("SELECT * FROM `studentinfo` WHERE title = '$str'");

            $sth->setFetchMode(PDO::FETCH_OBJ);
            $sth->execute();

            if ($row = $sth->fetch()) {
                echo '
                <div class="converted-text">
                <h2>Translated Text:</h2>
                <p>' . $row->content . '</p>';
            } else {
                echo '<p class="error-msg">Name does not exist</p>';
            }
        }
        ?>
        <br>
        <input type="submit" name="submit" value="Convert Into English" onclick="translateText()">
        <div class="converted-text">
            <h2>Translated Text:</h2>
            <p id="englishText"></p>
        </div>
    </div>
</body>

<script>
function translateText() {
    var tamilText = "<?php echo $row->content; ?>"; 
    var englishTextElement = document.getElementById("englishText");
    var chunks = tamilText.match(/.{1,1000}/g); // Split text into chunks of 1000 characters
    var translatedText = "";

    function translateChunk(index) {
        if (index < chunks.length) {
            var xhr = new XMLHttpRequest();
            var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=ta&tl=en&dt=t&q=" + encodeURI(chunks[index]);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    for (var i = 0; i < response[0].length; i++) {
                        translatedText += response[0][i][0] + ' ';
                    }
                    translateChunk(index + 1);
                }
            };
            xhr.open("GET", url, true);
            xhr.send();
        } else {
            englishTextElement.textContent = translatedText.trim();
        }
    }
    translateChunk(0);
}
</script>
</html>
