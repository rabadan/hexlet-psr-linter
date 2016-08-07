<?php

namespace HexletPsrLinter\Check;

use HexletPsrLinter\Checks\SideEffectsCheck;
use HexletPsrLinter\Visitor\NodeVisitor;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class SideEffectsCheckTest extends \PHPUnit_Framework_TestCase
{
    private $parser;
    private $traverser;
    private $check;

    public function setUp()
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser;
        $this->check = new SideEffectsCheck();
        $visitor = new NodeVisitor([
            $this->check
        ], false);

        $this->traverser->addVisitor($visitor);
    }

    public function testSideEffectsGood()
    {
        $this->check->reset();
        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/good/noSideEffects.php");
        $stmts = $this->parser->parse($codeSideEffectsGood);
        $this->traverser->traverse($stmts);
        $this->assertFalse($this->check->isSideEffects());

        $this->check->reset();
        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/good/TestClass.php");
        $stmts = $this->parser->parse($codeSideEffectsGood);
        $this->traverser->traverse($stmts);
        $this->assertFalse($this->check->isSideEffects());
    }

    public function testSideEffectsBad()
    {
        $this->check->reset();

        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/bad/sideEffects.php");
        $stmts = $this->parser->parse($codeSideEffectsGood);
        $this->traverser->traverse($stmts);

        $this->assertTrue($this->check->isSideEffects());
    }
}
