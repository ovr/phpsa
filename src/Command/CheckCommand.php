<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MyNodeVisitor extends \PhpParser\NodeVisitorAbstract {
    private $tokens;
    public function setTokens(array $tokens) {
        $this->tokens = $tokens;
    }

    public function leaveNode(\PhpParser\Node $node) {
        if ($node instanceof PhpParser\Node\Stmt\Property) {
            var_dump(isDeclaredUsingVar($this->tokens, $node));
        }
    }
}

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('SPA')
            ->setDefinition(array(
                new InputArgument('path', InputArgument::REQUIRED),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $lexer = new \PhpParser\Lexer(array(
        'usedAttributes' => array(
          'comments', 'startLine', 'endLine', 'startTokenPos', 'endTokenPos'
        )
      ));


      $parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
      // $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
      // $parser = new \PhpParser\Parser($lexer);

      // $visitor = new MyNodeVisitor();
      // $traverser = new \PhpParser\NodeTraverser();
      // $traverser->addVisitor($visitor);

      try {
        $code = file_get_contents(__DIR__ . '/../../tests/simple/test-1/1.php');

        $stmts = $parser->parse($code);
        // $visitor->setTokens($lexer->getTokens());
        // $stmts = $traverser->traverse($stmts);

        var_dump($stmts);

      } catch (PhpParser\Error $e) {
        echo 'Parse Error: ', $e->getMessage();
      }
    }
}
