<?php

namespace HexletPsrLinter\Visitor;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class SideEffectsVisitorTest extends \PHPUnit_Framework_TestCase
{
    public function testSideEffects()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new SideEffectsVisitor();
        $traverser->addVisitor($visitor);


        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/good/noSideEffects.php");
        $stmts = $parser->parse($codeSideEffectsGood);
        $traverser->traverse($stmts);
        $this->assertTrue($visitor->isValid());

        $codeSideEffectsGood = file_get_contents(__DIR__ . "/../fixtures/good/TestClass.php");
        $stmts = $parser->parse($codeSideEffectsGood);
        $traverser->removeVisitor($visitor);
        $visitor = new SideEffectsVisitor();
        $traverser->addVisitor($visitor);
        $traverser->traverse($stmts);
        $this->assertTrue($visitor->isValid());


        $codeSideEffectsBad = file_get_contents(__DIR__ . "/../fixtures/bad/sideEffects.php");
        $stmts = $parser->parse($codeSideEffectsBad);
        $traverser->removeVisitor($visitor);
        $visitor = new SideEffectsVisitor();
        $traverser->addVisitor($visitor);
        $traverser->traverse($stmts);
        $this->assertFalse($visitor->isValid());
    }
}
