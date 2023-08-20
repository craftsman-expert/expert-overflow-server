<?php

namespace App\Entity;

use App\Repository\UserSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'users_sessions')]
#[ORM\Entity(repositoryClass: UserSessionRepository::class)]
class UserSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    /**
     * Пользователь системы.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY', inversedBy: 'sessions')]
    private ?User $user;

    /**
     * Признак актуальности.
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $actual = true;

    /**
     * Наименование браузера.
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $browser_name = null;

    /**
     * Версия браузера.
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $browser_version = null;

    /**
     * Наименование операционной системы под которой запущен браузер.
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $os_name = null;

    /**
     * Версия операционной системы.
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $os_version = null;

    /**
     * Идентификационная строка клиентского приложения, использующая определённый сетевой протокол.
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_agent = null;

    /**
     * IP адрес.
     */
    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $ip = null;

    /**
     * Токен обновления - это особый вид токена, используемый для получения обновленного токена доступа.
     */
    #[ORM\Column(type: 'string', length: 40, nullable: true, unique: true)]
    private ?string $refresh_token;

    /**
     * Дата и время обновления refresh token.
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime $refresh_token_at;

    /**
     * Дата и время создания текущей записи.
     */
    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    /**
     * User constructor.
     */
    public function __construct(User $user, string $refresh_token, string|null $ip = null, string|null $user_agent = null)
    {
        $this->user = $user;
        $this->refresh_token = $refresh_token;
        $this->ip = $ip;
        $this->user_agent = $user_agent;
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isActual(): bool
    {
        return $this->actual;
    }

    public function setActual(bool $actual): void
    {
        $this->actual = $actual;
    }

    public function setRefreshToken(?string $refresh_token): void
    {
        $this->refresh_token = $refresh_token;
    }

    public function getBrowserName(): ?string
    {
        return $this->browser_name;
    }

    public function setBrowserName(?string $browser_name): void
    {
        $this->browser_name = $browser_name;
    }

    public function getBrowserVersion(): ?string
    {
        return $this->browser_version;
    }

    public function setBrowserVersion(?string $browser_version): void
    {
        $this->browser_version = $browser_version;
    }

    public function getOsName(): ?string
    {
        return $this->os_name;
    }

    public function setOsName(?string $os_name): void
    {
        $this->os_name = $os_name;
    }

    public function getOsVersion(): ?string
    {
        return $this->os_version;
    }

    public function setOsVersion(?string $os_version): void
    {
        $this->os_version = $os_version;
    }

    public function getRefreshTokenAt(): \DateTime
    {
        return $this->refresh_token_at;
    }

    public function setRefreshTokenAt(\DateTime $refresh_token_at): void
    {
        $this->refresh_token_at = $refresh_token_at;
    }
}
