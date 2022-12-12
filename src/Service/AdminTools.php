<?php

namespace App\Service;

/**
 * Reçoit les données du formulaire des outils de la page d'administration
 */
class AdminTools
{
    private ?string $category = null;
    private ?int $categoryPosition = 0;
    private ?bool $categoryModerationOnly = null;
    private ?string $subCategory = null;
    private ?string $parentCategoryName = null;
    private ?int $subCategoryPosition = 0;
    private ?string $subCategoryDescription = null;
    
    public function getCategory(): ?string
    {
        return $this->category;
    }
    
    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }
    
    public function getCategoryPosition(): ?int
    {
        return $this->categoryPosition;
    }
    
    public function setCategoryPosition(?int $categoryPosition): self
    {
        $this->categoryPosition = $categoryPosition;
        return $this;
    }
    
    public function getCategoryModerationOnly(): ?bool
    {
        return $this->categoryModerationOnly;
    }
    
    public function setCategoryModerationOnly(?bool $categoryModerationOnly): self
    {
        $this->categoryModerationOnly = $categoryModerationOnly;
        return $this;
    }
    
    public function getSubCategory(): ?string
    {
        return $this->subCategory;
    }
    
    public function setSubCategory(?string $subCategory): self
    {
        $this->subCategory = $subCategory;
        return $this;
    }
    
    public function getParentCategoryName(): ?string
    {
        return $this->parentCategoryName;
    }
    
    public function setParentCategoryName(?string $parentCategoryName): self
    {
        $this->parentCategoryName = $parentCategoryName;
        return $this;
    }
    
    public function getSubCategoryPosition(): ?int
    {
        return $this->subCategoryPosition;
    }
    
    public function setSubCategoryPosition(?int $subCategoryPosition): self
    {
        $this->subCategoryPosition = $subCategoryPosition;
        return $this;
    }
    
    public function getSubCategoryDescription(): ?string
    {
        return $this->subCategoryDescription;
    }
    
    public function setSubCategoryDescription(?string $subCategoryDescription): self
    {
        $this->subCategoryDescription = $subCategoryDescription;
        return $this;
    }
}
