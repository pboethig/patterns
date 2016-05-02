<?php
/**
 *
 * Interface ITranslator
 */
interface IMarkupTranslator
{

    public function translate();

    public function getFontfamily();

    public function getParagraphStyle();

    public function getFontName();

    public function getFontSize();

    public function getFontColor();

    public function getAlignment();
}

/**
 * Interface IFieldRuleInterface
 */
interface IFieldRuleInterface
{
    public function __construct(Frame $frame, IProtectionEditorProxy $ProtectionEditorProxy, SplObjectStorage $dependencyStorage);

    public function setProtectionEditorProxy(IProtectionEditorProxy $ProtectionEditorProxy);

    public function setDependencyStorage(SplObjectStorage $dependencyStorage);

    public function getAdapter();

    public function setDefaultContent(Content $content);

    public function setFinalContent(Content $content);

    public function getFinalContent();

    public function write(Frame $frame);
}

/**
 * Interface IWysiwygEditor
 */
interface IWysiwygEditor
{
    public function save(Content $content);
}

/**
 * Interface IContent
 */
interface IContent
{
    public function getType();
}


interface IProtectionEditorProxy
{
    public function getPropertyAcl();

    public function setPropertyAcl(SplObjectStorage $acl);
}

/**
 * Class ProtectionEditorProxy
 */
class ProtectionEditorProxy implements IProtectionEditorProxy
{

    public function __construct(SplObjectStorage $acl)
    {
        $this->setPropertyAcl($acl);
    }

    /**
     * @var SplObjectStorage
     */
    private $_propertyAcl;

    public function getPropertyAcl()
    {
        return $this->_propertyAcl;
    }

    public function setPropertyAcl(SplObjectStorage $acl)
    {
        $this->_propertyAcl = $acl;
    }
}

class BBCodeToIbramsTaggedTextTranslator implements IMarkupTranslator
{
    /**
     * @var Text
     */
    private $_text;

    public function __construct(Text $content)
    {
        $this->_text = $content;
    }

    public function translate()
    {

    }

    public function getFontfamily()
    {
        // TODO: Implement getFontfamily() method.
    }

    public function getParagraphStyle()
    {
        // TODO: Implement getParagraphStyle() method.
    }

    public function getFontName()
    {
        // TODO: Implement getFontName() method.
    }

    public function getFontSize()
    {
        // TODO: Implement getFontSize() method.
    }

    public function getFontColor()
    {
        // TODO: Implement getFontColor() method.
    }

    public function getAlignment()
    {
        // TODO: Implement getAlignment() method.
    }
}


/**
 * Converts an iBrams tagged text to BBCode
 *
 * Class IbramsTaggedTextToBBCodeTranslator
 */
class IbramsTaggedTextToBBCodeTranslator implements IMarkupTranslator
{
    /**
     * @var String
     */
    private $_text;

    public function __construct(Text $content)
    {
        $this->_text = $content;
    }

    public function translate()
    {
        // TODO: Implement translate() method.
    }

    public function getFontfamily()
    {
        // TODO: Implement getFontfamily() method.
    }

    public function getParagraphStyle()
    {
        // TODO: Implement getParagraphStyle() method.
    }

    public function getFontName()
    {
        // TODO: Implement getFontName() method.
    }

    public function getFontSize()
    {
        // TODO: Implement getFontSize() method.
    }

    public function getFontColor()
    {
        // TODO: Implement getFontColor() method.
    }

    public function getAlignment()
    {
        // TODO: Implement getAlignment() method.
    }
}

/**
 * Interface IFieldRuleDataSource
 */
interface IFieldRuleDataSource
{
    public function query(Frame $frame);

    public function write(Frame $frame);

    public function connect(DatasourceConnection $connection);
}

/**
 * Class IbramsTaggedTextFieldRule
 */
class IbramsTaggedTextFieldRule implements IFieldRuleInterface
{
    /**
     * @var IFrame
     */
    private $_frame;

    /**
     * @var Text
     */
    private $_text;

    /**
     * @var IMarkupTranslator
     */
    private $_translator;

    /**
     * @var I
     */
    private $_adapter;

    /**
     * @var SplObjectStorage
     */
    private $_dependencyStorage;

    /**
     * @var Logger
     */
    private $_logger;

    /**
     * @var Content
     */
    private $_defaultContent;

    /**
     * @var IProtectionEditorProxy
     */
    private $_ProtectionEditorProxy;

    public function __construct(Frame $frame, IProtectionEditorProxy $ProtectionEditorProxy, SplObjectStorage $dependencyStorage)
    {
        $this->_frame = $frame;

        $this->setProtectionEditorProxy($ProtectionEditorProxy);

        $this->setDependencyStorage($dependencyStorage);

        $this->_logger = DI::get($this->_dependencyStorage, 'logger');

        $defaultContent = $this->setDefaultContent($this->query($this->_frame));
    }

    public function setProtectionEditorProxy(IProtectionEditorProxy $ProtectionEditorProxy)
    {
        $this->_ProtectionEditorProxy = $ProtectionEditorProxy;
    }


    public function setDefaultContent(Content $contentObject)
    {
        $this->_logger->log(PHP_EOL .  __METHOD__ .PHP_EOL . '=> ' . $contentObject->getFinalContent());
        
        $this->_defaultContent = $contentObject;
    }


    public function setFinalContent(Content $contentObject)
    {

    }


    public function query(Frame $frame)
    {
        return $this->getAdapter()->query($frame);
    }

    public function translate(Content $content)
    {

    }

    public function getFinalContent()
    {
        return $this->_text;
    }


    public function getAdapter()
    {
        return new FieldRuleDataSource(new DatasourceConnection());
    }

    public function setDependencyStorage(SplObjectStorage $dependencyStorage)
    {
        $this->_dependencyStorage = $dependencyStorage;
    }

    public function write(Frame $frame)
    {
        $this->getAdapter()->write($frame);
    }
}

/**
 * Class DatasourceConnection
 */
class DatasourceConnection
{

}

/**
 * Class FieldRuleDataSource
 */
class FieldRuleDataSource implements  IFieldRuleDataSource
{

    private $_connection;

    public function __construct(DataSourceConnection $connection)
    {
        $this->_connection = $connection;

        $this->connect($connection);
    }

    public function query(Frame $frame)
    {

        $content = new Content("[B]Lorem ipsum dolor si[/B]t amet, consetetur sadipscing elitr, sed diam nonumy eirmod tem[SIZE=6]por invidunt ut labore et d[/SIZE]olore magna aliquyam erat, sed diam voluptua. At[COLOR=#FFC000] vero eos et ac[/COLOR]cusam et justo duo dolor[SUB]es et ea rebum. Stet clita kasd gubergren, no s[/SUB]ea takimata sanctus est Lorem ips[B]um dolor sit amet. Lore[/B]m ipsum dolor si[I]t amet,[/I]
        d diam nonu[FONT=Book Antiqua]my eirmod tempor invidunt ut l[/FONT]abore et dolore [PRE]magna aliquyam erat, sed diam voluptua. [/PRE]At vero eos et accusam et justo duo dolores et ea[U] rebum. Stet clit[/U]a kasd gubergren, no sea takimata s[I]anctus est Lorem ipsu[/I]m dolor sit amet.");

        return $content;

    }

    public function write(Frame $frame)
    {
        // TODO: Implement write() method.
    }

    public function connect(DatasourceConnection $connection)
    {
        // TODO: Implement connect() method.
    }
}

/**
 * Class BBCodeWysiwygEditor
 */
class BBCodeWysiwygEditor implements IWysiwygEditor
{
    public function save(Content $content)
    {
        // TODO: Implement save() method.
    }
}

/**
 * Class Content
 */
class Content implements IContent
{

    private $_type;

    /**
     * @var String
     */
    private $_lang;

    /**
     * @var String
     */
    private $_rtlLtR;

    /**
     * @var integer
     */
    private $_wordCount;

    /**
     * @var integer
     */
    private $_characterCount;

    /**
     * @var Text
     */
    private $_text;

    /**
     * @var SplObjectStorage
     */
    private $_paragraphs;

    /**
     * @var SplObjectStorage
     */
    private $_fonts;


    public function __construct(String $content)
    {
        $this->_text = $content;
    }

    /**
     * @return String
     */
    public function getLang()
    {
        return $this->_lang;
    }

    /**
     * @param String $lang
     */
    public function setLang($lang)
    {
        $this->_lang = $lang;
    }

    /**
     * @return String
     */
    public function getRtlLtR()
    {
        return $this->_rtlLtR;
    }

    /**
     * @param String $rtlLtR
     */
    public function setRtlLtR($rtlLtR)
    {
        $this->_rtlLtR = $rtlLtR;
    }

    /**
     * @return int
     */
    public function getWordCount()
    {
        return $this->_wordCount;
    }

    /**
     * @param int $wordCount
     */
    public function setWordCount($wordCount)
    {
        $this->_wordCount = $wordCount;
    }

    /**
     * @return int
     */
    public function getCharacterCount()
    {
        return $this->_characterCount;
    }

    /**
     * @param int $characterCount
     */
    public function setCharacterCount($characterCount)
    {
        $this->_characterCount = $characterCount;
    }

    /**
     * @return Text
     */
    public function getFinalContent()
    {
        return $this->_text;
    }

    /**
     * @param Text $content
     */
    public function setFinalContent(Text $content)
    {
        $this->_text = $content;
    }

    /**
     * @return SplObjectStorage
     */
    public function getParagraphs()
    {
        return $this->_paragraphs;
    }

    /**
     * @param SplObjectStorage $paragraphs
     */
    public function setParagraphs($paragraphs)
    {
        $this->_paragraphs = $paragraphs;
    }

    /**
     * @return SplObjectStorage
     */
    public function getFonts()
    {
        return $this->_fonts;
    }

    /**
     * @param SplObjectStorage $fonts
     */
    public function setFonts($fonts)
    {
        $this->_fonts = $fonts;
    }

    public function getType()
    {
        return $this->_type;
    }
}

class PropertyAcl
{

    /**
     * @var SplObjectStorage
     */
    private $_storage;

    public function __construct(SplObjectStorage $objectStorage)
    {
        $this->_storage = $objectStorage;
    }

    /**
     * @return SplObjectStorage
     */
    public function getStorage()
    {
        return $this->_storage;
    }
}

interface IFrame
{

    public function getFinalContent();

}

class Frame implements IFrame
{
    public function __construct()
    {

    }

    public function getFinalContent()
    {
        // TODO: Implement getFinalContent() method.
    }


}

/**
 * Class Logger
 */
class Logger
{
    public static function log(String $message)
    {
        echo $message . PHP_EOL;
    }
}

/**
 * Class DI
 *
 * Resolves SplStprage based dependency objectlists
 *
 */
class DI
{
    /**
     * Returns object by info tag.
     *
     * @param SplObjectStorage $dependency
     * @param String $searchTerm
     * @return null|object
     */
    public static function get(SplObjectStorage $dependency, String $searchTerm)
    {
        $dependency->rewind();

        while($dependency->valid())
        {
            if($dependency->getInfo() == $searchTerm)
            {
                return $dependency->current();
            }

            $dependency->next();
        }

        return null;
    }
}

$dependencies = new SplObjectStorage();

$dependencies->attach(new Logger(), 'logger');

$acl = new PropertyAcl(new SplObjectStorage());

$fieldRuleObject = new IbramsTaggedTextFieldRule(new Frame(), new ProtectionEditorProxy($acl->getStorage()), $dependencies);