<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

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
    const CATEGORY_Clarity = 'Clarity';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_Compatibility = 'Compatibility';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_Complexity = 'Complexity';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_Duplication = 'Duplication';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_Performance = 'Performance';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_Security = 'Security';

    /**
     * @todo Description
     *
     * @var string
     */
    const CATEGORY_Style = 'Style';

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
     * @var string
     */
    protected $location;

    /**
     * @param string $checkName
     * @param string $description
     * @param array $location
     * @param array $categories
     */
    public function __construct($checkName, $description, array $location, array $categories = [self::CATEGORY_BUG_RISK])
    {
        $this->checkName = $checkName;
        $this->description = $description;
        $this->location = $location;
        $this->categories = $categories;
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'description' => $this->description,
            'location' => $this->location,
            'categories' => $this->categories,
        ];
    }
}
