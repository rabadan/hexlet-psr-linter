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

    public function testSideEffectsGood()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $check = new SideEffectsCheck();
        $visitor = new NodeVisitor([$check], false);
        $traverser->addVisitor($visitor);

        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/good/noSideEffects.php");
        $stmts = $parser->parse($codeSideEffectsGood);
        $traverser->traverse($stmts);
        $this->assertFalse($check->isSideEffects());

        $traverser->removeVisitor($visitor);
        $check = new SideEffectsCheck();
        $visitor = new NodeVisitor([$check], false);
        $traverser->addVisitor($visitor);
        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/good/TestClass.php");
        $stmts = $parser->parse($codeSideEffectsGood);
        $traverser->traverse($stmts);
        $this->assertFalse($check->isSideEffects());
    }

    public function testSideEffectsBad()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $check = new SideEffectsCheck();
        $visitor = new NodeVisitor([$check], false);
        $traverser->addVisitor($visitor);

        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/bad/sideEffects.php");
        $stmts = $parser->parse($codeSideEffectsGood);
        $traverser->traverse($stmts);

        $this->assertTrue($check->isSideEffects());
    }
}
