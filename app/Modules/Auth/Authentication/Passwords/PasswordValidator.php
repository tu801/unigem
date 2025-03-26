<?php

namespace Modules\Auth\Authentication\Passwords;

use Modules\Auth\Config\Auth as AuthConfig;
use Modules\Auth\Exceptions\AuthException;
use Modules\Acp\Models\User\UserModel;

class PasswordValidator extends BaseValidator
{
	/**
	 * @var AuthConfig
	 */
	protected $config;

	protected $error;

	protected $suggestion;

	public function __construct(AuthConfig $config)
	{
		$this->config = $config;
	}

	/**
	 * Checks a password against all of the Validators specified
	 * in `$passwordValidators` setting in Config\Auth.php.
	 *
	 * @param string $password
	 * @param User   $user
	 *
	 * @return bool
	 */
	public function check(string $password, $user = null): bool
	{
		if (is_null($user))
		{
			throw AuthException::forNoEntityProvided();
		}

		$password = trim($password);

		if (empty($password))
		{
			$this->error = lang('Auth.errorPasswordEmpty');

			return false;
		}

		$valid = false;

		foreach ($this->config->passwordValidators as $className)
		{
			$class = new $className();
			$class->setConfig($this->config);

			if ($class->check($password, $user) === false)
			{
				$this->error = $class->error();
				$this->suggestion = $class->suggestion();

				$valid = false;
				break;
			}

			$valid = true;
		}

		return $valid;
	}

    /**
     * Returns the error string that should be displayed to the user.
     */
    public function error(): string
    {
        return $this->error;
    }

    /**
     * Returns a suggestion that may be displayed to the user
     * to help them choose a better password. The method is
     * required, but a suggestion is optional. May return
     * an empty string instead.
     */
    public function suggestion(): string
    {
        return $this->suggestion;
    }
}
