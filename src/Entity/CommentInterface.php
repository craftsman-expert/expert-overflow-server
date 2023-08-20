<?php

namespace App\Entity;

use DateTimeInterface;

interface CommentInterface
{
    public function getAuthor(): User;
    public function getCreatedAt(): DateTimeInterface;
}