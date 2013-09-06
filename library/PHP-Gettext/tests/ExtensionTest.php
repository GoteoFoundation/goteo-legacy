<?php
//require_once 'PHPUnit/Framework.php';
require_once '../Gettext.php';

class ExtensionTest extends PHPUnit_Framework_TestCase
{
    public function testGettext()
    {
        $g = new Gettext_Extension('./', 'gettext', 'de');
        $this->assertEquals('Datei existiert nicht', $g->gettext('File does not exist'));
        $this->assertEquals('Datei ist zu klein', $g->gettext('File is too small'));
        $this->assertEquals('Foobar', $g->gettext('Foobar'));
        $this->assertContains('Last-Translator', $g->gettext(null));
    }

    public function testNonexistantFile()
    {
        $g = new Gettext_Extension('./', 'gettext', 'notexistent');
        $this->assertEquals('Foobar', $g->gettext('Foobar'));
    }

    public function testNgettext()
    {
        $g = new Gettext_Extension('./', 'gettext', 'de');
        $this->assertEquals('Datei existiert nicht', $g->ngettext('File does not exist', 'Files donnot exists', 1));
        $this->assertEquals('Datei ist zu klein', $g->ngettext('File is too small', 'Files are too small', 1));
        $this->assertEquals('Foobar', $g->ngettext('Foobar', 'Foobar', 1));

        $this->assertEquals('Datei existiert nicht', $g->ngettext('File does not exist', 'Files donnot exists', 2));
        $this->assertEquals('Dateien sind zu klein', $g->ngettext('File is too small', 'Files are too small', 2));
        $this->assertEquals('Foobar', $g->ngettext('Foobar', 'Foobar', 2));

        $this->assertContains('Last-Translator', $g->ngettext(null, null, 1));

        $this->assertEquals('Dateien sind zu klein', $g->ngettext('File is too small', 'Files are too small', -1));
        $this->assertEquals('Dateien sind zu klein', $g->ngettext('File is too small', 'Files are too small', 0));
    }
}

