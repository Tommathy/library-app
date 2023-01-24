<?php

class Book
{

    private string $title;
    private string $author;
    private int $publication_year;
    private int $number_of_page;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return int
     */
    public function getPublicationYear(): int
    {
        return $this->publication_year;
    }

    /**
     * @return int
     */
    public function getNumberOfPage(): int
    {
        return $this->number_of_page;
    }

}
