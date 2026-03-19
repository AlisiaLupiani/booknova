<?php

require_once("include/model/Libro.php");


class BookProxy extends Libro{

    private ?DataLayer $dataLayer;

    private int $authorId;
    private int $publisherId;
    private int $conditionId;
    private int $categoryId;
    private int $formatId;


    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getAuthorId(): int {return $this->authorId}
    public function setAuthorId(int $authorId): void {$this->authorId = $authorId}

    public function getPublisherId(): int {return $this->publisherId}
    public function setPublisherId(int $publisherId): void {$this->publisherId = $publisherId}

    public function getConditionId(): int {return $this->conditionId}
    public function setConditionId(int $conditionId): void {$this->conditionId = $conditionId}

    public function getCategoryId(): int {return $this->categoryId}
    public function setCategoryId(int $categoryId): void {$this->categoryId = $categoryId}

    public function getFormatId(): int {return $this->formatId}
    public function setFormatId(int $formatId): void {$this->formatId = $formatId}


    public function getAuthor(): ?Author{
        if(parent::getAuthor() == null && $this->authorId > 0){
            parent::setAuthor((($this->dataLayer)->getAuthorDAO())->getAuthorById($this->authorId));
        }
        return parent::getAuthor();
    }

}
