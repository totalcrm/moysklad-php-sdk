<?php

namespace TotalCRM\MoySklad\Entities\Documents;

use Exception;
use Throwable;
use TotalCRM\MoySklad\Components\Http\RequestConfig;
use TotalCRM\MoySklad\Components\Http\RequestLog;
use TotalCRM\MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Documents\Templates\AbstractTemplate;
use TotalCRM\MoySklad\Entities\Documents\Templates\CustomTemplate;
use TotalCRM\MoySklad\Entities\Documents\Templates\EmbeddedTemplate;
use TotalCRM\MoySklad\Entities\Misc\Attribute;
use TotalCRM\MoySklad\Entities\Misc\Export;
use TotalCRM\MoySklad\Entities\Misc\Publication;
use TotalCRM\MoySklad\Exceptions\EntityHasNoIdException;
use TotalCRM\MoySklad\Exceptions\IncompleteCreationFieldsException;
use TotalCRM\MoySklad\Exceptions\UnknownEntityException;
use TotalCRM\MoySklad\Lists\EntityList;
use TotalCRM\MoySklad\MoySklad;
use TotalCRM\MoySklad\Registers\ApiUrlRegistry;

class AbstractDocument extends AbstractEntity
{
    public static string $entityName = 'a_document';

    /**
     * @param MoySklad $sklad
     * @param Attribute $attribute
     * @return \stdClass|string
     * @throws Throwable
     */
    public static function getAttributeMetaData(MoySklad $sklad, Attribute $attribute)
    {
        return $sklad->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataAttributeUrl(static::$entityName, $attribute->id)
        );
    }

    /**
     * Create document template
     * @param bool $makeEmptyTemplate
     * @return \stdClass|string
     * @throws Exception
     * @throws Throwable
     */
    public function newTemplate($makeEmptyTemplate = false)
    {
        $requestConfig = new RequestConfig();
        if ($makeEmptyTemplate) {
            $requestConfig->set("ignoreRequestBody", true);
        }
        return $this->getSkladInstance()->getClient()->put(
            ApiUrlRegistry::instance()->getNewDocumentTemplateUrl(static::$entityName),
            $this->mergeFieldsWithLinks(),
            $requestConfig
        );
    }

    /**
     * @param QuerySpecs $querySpecs
     * @return EntityList
     * @throws EntityHasNoIdException
     */
    public function getPublications(QuerySpecs $querySpecs = null): EntityList
    {
        return Publication::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getDocumentPublicationsUrl($this::$entityName, $this->findEntityId()))
            ->getList();
    }

    /**
     * @param CustomTemplate $template
     * @return Publication
     * @throws EntityHasNoIdException
     * @throws IncompleteCreationFieldsException
     * @throws Throwable
     */
    public function createPublication(CustomTemplate $template)
    {
        $template->validateFieldsRequiredForCreation();
        $res = $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getDocumentPublicationsUrl(static::$entityName, $this->findEntityId()),
            [
                "template" => $template->mergeFieldsWithLinks()
            ]
        );
        return new Publication($this->getSkladInstance(), $res);
    }

    /**
     * @param Publication $publication
     * @return bool
     * @throws EntityHasNoIdException
     * @throws Throwable
     */
    public function deletePublication(Publication $publication)
    {
        $this->getSkladInstance()->getClient()->delete(
            ApiUrlRegistry::instance()->getDocumentPublicationWithIdUrl(static::$entityName, $this->findEntityId(), $publication->findEntityId())
        );
        return true;
    }

    /**
     * @param $id
     * @return Publication
     * @throws EntityHasNoIdException
     * @throws Throwable
     */
    public function getPublicationById($id)
    {
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getDocumentPublicationWithIdUrl(static::$entityName, $this->findEntityId(), $id)
        );
        return new Publication($this->getSkladInstance(), $res);
    }

    /**
     * @param CustomTemplate|EntityList $templateOrTemplates
     * @param string $extension
     * @return Export
     * @throws Exception
     * @throws EntityHasNoIdException
     * @throws Throwable
     */
    public function createExport($templateOrTemplates, $extension = 'pdf'): Export
    {
        $supportedExtensions = ['xls', 'pdf', 'html', 'ods'];
        if (!in_array($extension, $supportedExtensions, true)) {
            throw new \RuntimeException("Extension must be one of: " . implode(',', $supportedExtensions));
        }
        if ($templateOrTemplates instanceof EntityList) {
            foreach ($templateOrTemplates as $template) {
                if (empty($template->count) || $template->count <= 0) {
                    $template->count = 1;
                }
                else if ($template->count > 10) {
                    throw new \RuntimeException("Template count field is more then 10");
                }
            }
            $exportRequest = [
                "templates" => $templateOrTemplates->map(function (AbstractTemplate $template) {
                    return [
                        "template" => $template,
                        "count" => $template->count
                    ];
                })
            ];
        } else if ($templateOrTemplates instanceof AbstractTemplate) {
            $exportRequest = [
                "template" => $templateOrTemplates,
                "extension" => $extension,
            ];
        } else {
            throw new Exception("First argument must be either template or EntityList of templates");
        }
        $res = $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getDocumentExportUrl(static::$entityName, $this->findEntityId()),
            $exportRequest,
            new RequestConfig(['followRedirects' => false])
        );
        return new Export($this->getSkladInstance(), [
            'file' => $res
        ]);
    }

    /**
     * @param QuerySpecs $querySpecs
     * @return EntityList
     * @throws Exception
     */
    public function getExportEmbeddedTemplates(QuerySpecs $querySpecs = null): EntityList
    {
        return EmbeddedTemplate::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getMetadataExportEmbeddedTemplateUrl(static::$entityName))
            ->getList();
    }

    /**
     * @param QuerySpecs $querySpecs
     * @return EntityList
     * @throws Exception
     */
    public function getExportCustomTemplates(QuerySpecs $querySpecs = null): EntityList
    {
        return CustomTemplate::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getMetadataExportCustomTemplateUrl(static::$entityName))
            ->getList();
    }

    /**
     * @param $id
     * @return CustomTemplate
     * @throws Throwable
     */
    public function getExportCustomTemplateById($id): CustomTemplate
    {
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataExportCustomTemplateWithIdUrl(static::$entityName, $id)
        );
        return new CustomTemplate($this->getSkladInstance(), $res);
    }

    /**
     * @param $id
     * @return EmbeddedTemplate
     * @throws Throwable
     */
    public function getExportEmbeddedTemplateById($id): EmbeddedTemplate
    {
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataExportEmbeddedTemplateWithIdUrl(static::$entityName, $id)
        );
        return new EmbeddedTemplate($this->getSkladInstance(), $res);
    }
}
