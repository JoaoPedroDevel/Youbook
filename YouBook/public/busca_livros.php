<?php
// session_start(); // Se necessário

$api_key = 'AIzaSyBd_HMsxljJR-F1PNSTnxWIZV9Z8xMyiU4'; // Substitua pela sua chave
$search_term = $_GET['q'] ?? ''; // Termo de busca vindo da URL ou formulário

if (empty($search_term)) {
    echo '<script>window.alert("Por favor, digite um títtulo")</script>';
    header('Location: ../public/dashboard.php');
    exit();
}

$encoded_search_term = urlencode($search_term);
$url = "https://www.googleapis.com/books/v1/volumes?q={$encoded_search_term}&filter=free-ebooks&key={$api_key}";

// Faz a requisição HTTP
$response = @file_get_contents($url); // O @ suprime warnings se a URL não existir/der erro

if ($response === FALSE) {
    echo "Erro ao buscar livros na API do Google Books.";
    // Em um sistema real, você registraria este erro
    error_log("Erro na API do Google Books para busca: " . $search_term);
    exit();
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Erro ao decodificar a resposta da API.";
    error_log("Erro JSON decode: " . json_last_error_msg());
    exit();
}

if (isset($data['items']) && !empty($data['items'])) {
    echo "<h1>Resultados da Busca por '{$search_term}' (Livros Gratuitos/eBooks):</h1>";
    echo "<ul>";
    foreach ($data['items'] as $item) {
        $volume_info = $item['volumeInfo'];
        $access_info = $item['accessInfo'];

        $title = htmlspecialchars($volume_info['title'] ?? 'Sem Título');
        $authors = htmlspecialchars(implode(', ', $volume_info['authors'] ?? ['Autor Desconhecido']));
        $description = htmlspecialchars($volume_info['description'] ?? 'Sem descrição.');
        $thumbnail = $volume_info['imageLinks']['thumbnail'] ?? 'no_image.png';

        $pdf_link = '';
        if (isset($access_info['pdf']['isAvailable']) && $access_info['pdf']['isAvailable'] === true) {
            // Muito importante: o downloadLink só aparece para livros onde o Google tem direitos de download completo.
            // Isso é raro para a maioria dos livros, comum para domínio público.
            if (isset($access_info['pdf']['downloadLink'])) {
                $pdf_link = '<p><a href="' . htmlspecialchars($access_info['pdf']['downloadLink']) . '" target="_blank">Download PDF</a></p>';
            } else {
                $pdf_link = '<p>PDF disponível (apenas visualização online).</p>';
            }
        } else {
            $pdf_link = '<p>PDF não disponível.</p>';
        }

        $web_reader_link = $access_info['webReaderLink'] ?? '';

        echo "<li>";
        echo "<h3>" . $title . "</h3>";
        echo "<p><strong>Autor(es):</strong> " . $authors . "</p>";
        echo "<img src='" . htmlspecialchars($thumbnail) . "' alt='" . $title . "' style='max-width: 100px;'>";
        echo "<p>" . substr($description, 0, 200) . "...</p>"; // Limita a descrição
        echo $pdf_link;
        if (!empty($web_reader_link)) {
            echo '<p><a href="' . htmlspecialchars($web_reader_link) . '" target="_blank">Ler Online (Google Books)</a></p>';
        }
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Nenhum livro gratuito encontrado para '{$search_term}'.</p>";
}
?>