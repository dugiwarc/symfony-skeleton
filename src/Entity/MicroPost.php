<?php

namespace App\Entity;

use App\Repository\MicroPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Comment;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MicroPostRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{



    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
        $this->signaledBy = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }

    public function getSignaledBy()
    {
        return $this->signaledBy;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $likedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsSignaled")
     * @ORM\JoinTable(name="post_signals",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $signaledBy;

    public function comment(Comment $comment) {
        $this->comments->add($comment);
    }

    public function signal(User $user)
    {
        if($this->signaledBy->contains($user)) {
            return;
        }

        $this->signaledBy->add($user);
    }

    public function like(User $user)
    {
        if($this->likedBy->contains($user)) {
            return;
        }

        $this->likedBy->add($user);
    }

    /**
     * @return Collection
     *
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\Length(min=10, "10 characters minimum please")
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
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
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist(): void
    {
        $this->time = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }


}
