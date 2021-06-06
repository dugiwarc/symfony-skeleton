<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    private $testId;

    /**
     * @var string;
     * @ORM\Column(type="string")
     */
    private $text;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $microPost;

    /**
     * @return mixed
     */
    public function getMicroPost()
    {
        return $this->microPost;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @param mixed $microPost
     */
    public function setMicroPost($microPost): void
    {
        $this->microPost = $microPost;
    }



    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTestId()
    {
        return $this->testId;
    }

    /**
     * @param mixed $testId
     */
    public function setTestId($testId): void
    {
        $this->testId = $testId;
    }
}
