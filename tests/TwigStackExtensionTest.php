<?php declare(strict_types=1);

namespace Crate\ViewTest\Twig\Extensions;

use Crate\View\Twig\Extensions\StackExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

final class TwigStackExtensionTest extends TestCase
{
    /**
     * TwigStackExtensionTest->twig
     *
     * @var Environment
     */
    private $twig;

    /**
     * TwigStackExtensionTest->setUp()
     *
     * Initialize the Twig Environment for each test case.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->twig = new Environment(new FilesystemLoader(__DIR__.'/templates'));
        $this->twig->addExtension(new StackExtension());
    }

    /**
     * TwigStackExtensionTest->testPushPrepend()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependViaExtends(): void
    {
        $result = trim($this->twig->render('via-extends/template.twig'));
        $expected = file_get_contents(__DIR__ . '/templates/via-extends/result.html');
        $this->assertSame($expected, $result);
    }
    
    /**
     * TwigStackExtensionTest->testPushPrepend()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependViaInclude(): void
    {
        $result = trim($this->twig->render('via-include/template.twig'));
        $expected = file_get_contents(__DIR__ . '/templates/via-include/result.html');
        $this->assertSame($expected, $result);
    }

    /**
     * TwigStackExtensionTest->testPushPrependViaEmbed()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependViaEmbed(): void
    {
        $result = trim($this->twig->render('via-embed/template.twig'));
        $this->assertMatchesRegularExpression('/^7\s+8\s+9$/', $result);
    }

    /**
     * TwigStackExtensionTest->testPushPrependOnce()
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testPushPrependOnce(): void
    {
        $result = trim($this->twig->render('via-embed-once/template.twig'));

        $lines = preg_split('/\s*\n+\s*/', $result);
        $this->assertEquals([
            '<script>MyScript = () => {}</script>',
            '<script>MyScript("First Action")</script>',
            '<script>MyScript("Second Action")</script>',
            '<script>MyScript("Third Action")</script>',
        ], $lines);
    }
}
