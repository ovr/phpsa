<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

/**
 * An Issue the compiler or an analyzer found
 */
class Issue
{
    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_BUG_RISK = 'Bug Risk';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_CLARITY = 'Clarity';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_COMPATIBILITY = 'Compatibility';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_COMPLEXITY = 'Complexity';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_DUPLICATION = 'Duplication';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_PERFORMANCE = 'Performance';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_SECURITY = 'Security';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_STYLE = 'Style';

    /**
     * Required. Must always be "issue".
     *
     * @var string
     */
    protected $type = 'issue';

    /**
     * Required. A unique name representing the static analysis check that emitted this issue.
     *
     * @var string
     */
    protected $checkName;

    /**
     * Required. A string explaining the issue that was detected.
     *
     * @var string
     */
    protected $description;

    /**
     * Optional. A markdown snippet describing the issue, including deeper explanations and links to other resources.
     *
     * @var string
     */
    protected $content;

    /**
     * Required. At least one category indicating the nature of the issue being reported.
     *
     * @var string
     */
    protected $categories;

    /**
     * Required. A Location object representing the place in the source code where the issue was discovered.
     *
     * @var IssueLocation
     */
    protected $location;

    /**
     * @param string $checkName
     * @param string $description
     * @param IssueLocation $location
     * @param array $categories
     */
    public function __construct($checkName, $description, IssueLocation $location, array $categories = [self::CATEGORY_BUG_RISK])
    {
        $this->checkName = $checkName;
        $this->description = $description;
        $this->location = $location;
        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->type,
            'check_name' => $this->checkName,
            'description' => $this->description,
            'location' => $this->location->toArray(),
            'categories' => $this->categories,
        ];
    }

    /**
     * @return IssueLocation
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCheckName()
    {
        return $this->checkName;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
