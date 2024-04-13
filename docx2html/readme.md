<h2>planned features:</h2>
<ul>
    <li>styles.xml support (using css classes)</li>
    <li>save_html function</li>
    <li>blocks properties properties</li>
    <li>headers, tables, images and other elements</li>
</ul>
<h2>Usage</h2>
<p>
    require_once "DocxToHtml.php";

    $handler = new Handler("path/to/your/file.docx");
    echo $handler->get_html(); // returns html code
    $handler->save_html("path/to/save"); // saves an html file
</p>