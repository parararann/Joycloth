<?php
$zip = new ZipArchive;
if ($zip->open('DRAFT BAB 1.docx') === TRUE) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    
    // Preserve paragraphs and line breaks by replacing closing tags with newlines
    $xml = str_replace('</w:p>', "\n\n", $xml);
    $xml = str_replace('</w:br>', "\n", $xml);
    $xml = str_replace('<w:br/>', "\n", $xml);
    $xml = str_replace('</w:tab>', "\t", $xml);
    
    // Strip XML tags to get clean text
    $text = strip_tags($xml);
    
    // Decode HTML entities
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    
    file_put_contents('DRAFT_BAB_1_extracted.txt', $text);
    echo "SUCCESS";
} else {
    echo "FAILED";
}
