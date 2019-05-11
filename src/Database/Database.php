<?php

namespace Database;

use Exception;
use Database\Validator\Authorization;
use Database\Validator\Structure;
use Database\Exception\Authorization as AuthorizationException;
use Database\Exception\Structure as StructureException;

class Database
{
    // System Core
    const SYSTEM_COLLECTION_COLLECTIONS = 0;
    const SYSTEM_COLLECTION_RULES = 'rules';
    const SYSTEM_COLLECTION_OPTIONS = 'options';

    // Project
    const SYSTEM_COLLECTION_PROJECTS = 'projects';
    const SYSTEM_COLLECTION_WEBHOOKS = 'webhooks';
    const SYSTEM_COLLECTION_KEYS = 'keys';
    const SYSTEM_COLLECTION_TASKS = 'tasks';
    const SYSTEM_COLLECTION_PLATFORMS = 'platforms';
    const SYSTEM_COLLECTION_USAGES = 'usages'; //TODO add structure

    // Auth, Account and Users (private to user)
    const SYSTEM_COLLECTION_USERS = 'users';
    const SYSTEM_COLLECTION_TOKENS = 'tokens';

    // Teams (shared among team members)
    const SYSTEM_COLLECTION_MEMBERSHIPS = 'memberships';
    const SYSTEM_COLLECTION_TEAMS = 'teams';

    // Storage
    const SYSTEM_COLLECTION_FILES = 'files';

    /**
     * @var array
     */
    protected $mocks = [
        'console' => [
            '$uid' => 'console',
            '$collection' => 'projects',
            '$permissions' => ['read' => ['*']],
            'name' => 'Appwrite',
            'description' => 'Appwrite core engine',
            'logo' => '',
            'teamId' => -1,
            'webhooks' => [],
            'keys' => [],
            'clients' => [
                'http://localhost',
                'https://localhost',
                'https://appwrite.io',
                'https://appwrite.test',
            ],
            'legalName' => '',
            'legalCountry' => '',
            'legalState' => '',
            'legalCity' => '',
            'legalAddress' => '',
            'legalTaxId' => '',
            'usersOauthBitbucketAppid' => '',
            'usersOauthBitbucketSecret' => '',
            'usersOauthFacebookAppid' => '',
            'usersOauthFacebookSecret' => '',
            'usersOauthGithubAppid' => '',
            'usersOauthGithubSecret' => '',
            'usersOauthGitlabAppid' => '',
            'usersOauthGitlabSecret' => '',
            'usersOauthGoogleAppid' => '',
            'usersOauthGoogleSecret' => '',
            'usersOauthInstagramAppid' => '',
            'usersOauthInstagramSecret' => '',
            'usersOauthLinkedinAppid' => '',
            'usersOauthLinkedinSecret' => '',
            'usersOauthMicrosoftAppid' => '',
            'usersOauthMicrosoftSecret' => '',
            'usersOauthTwitterAppid' => '',
            'usersOauthTwitterSecret' => '',
        ],
        self::SYSTEM_COLLECTION_COLLECTIONS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Collections',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Structure',
                    'key' => 'structure',
                    'type' => 'boolean',
                    'default' => false,
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Rules',
                    'key' => 'rules',
                    'type' => 'documents',
                    'default' => [],
                    'required' => true,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_RULES]
                    ],
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_RULES => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_RULES,
            '$permissions' => ['read' => ['*']],
            'name' => 'Collections Rule',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Label',
                    'key' => 'label',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Key',
                    'key' => 'key',
                    'type' => 'key',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Type',
                    'key' => 'type',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Default',
                    'key' => 'default',
                    'type' => 'wildcard',
                    'default' => '',
                    'required' => false,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Required',
                    'key' => 'required',
                    'type' => 'boolean',
                    'default' => true,
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Array',
                    'key' => 'array',
                    'type' => 'boolean',
                    'default' => true,
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Options',
                    'key' => 'options',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => false,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_OPTIONS]
                    ],
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_OPTIONS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_OPTIONS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Collections Rule Option',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Whitelist',
                    'key' => 'whitelist',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => true,

                ],
            ],
        ],
        self::SYSTEM_COLLECTION_USERS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_USERS,
            '$permissions' => ['read' => ['*']],
            'name' => 'User',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Email',
                    'key' => 'email',
                    'type' => 'email',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Status',
                    'key' => 'status',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Password',
                    'key' => 'password',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Password Update Date',
                    'key' => 'password-update',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Prefs',
                    'key' => 'prefs',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Registration Date',
                    'key' => 'registration',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Confirmation Status',
                    'key' => 'confirm',
                    'type' => 'boolean',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Reset',
                    'key' => 'reset',
                    'type' => 'boolean',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Bitbucket ID',
                    'key' => 'oauthBitbucket',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Bitbucket Access Token',
                    'key' => 'oauthBitbucketAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Facebook ID',
                    'key' => 'oauthFacebook',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Facebook Access Token',
                    'key' => 'oauthFacebookAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth GitHub ID',
                    'key' => 'oauthGithub',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth GitHub Access Token',
                    'key' => 'oauthGithubAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Gitlab ID',
                    'key' => 'oauthGitlab',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Gitlab Access Token',
                    'key' => 'oauthGitlabAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Google ID',
                    'key' => 'oauthGoogle',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Google Access Token',
                    'key' => 'oauthGoogleAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Instagram ID',
                    'key' => 'oauthInstagram',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Instagram Access Token',
                    'key' => 'oauthInstagramAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth LinkedIn ID',
                    'key' => 'oauthLinkedin',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth LinkedIn Access Token',
                    'key' => 'oauthLinkedinAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Microsoft ID',
                    'key' => 'oauthMicrosoft',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Microsoft Access Token',
                    'key' => 'oauthMicrosoftAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Twitter ID',
                    'key' => 'oauthTwitter',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Twitter Access Token',
                    'key' => 'oauthTwitterAccessToken',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Tokens',
                    'key' => 'tokens',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_TOKENS]
                    ],
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Memberships',
                    'key' => 'memberships',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_MEMBERSHIPS]
                    ],
                ]
            ],
        ],
        self::SYSTEM_COLLECTION_TOKENS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_TOKENS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Token',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Type',
                    'key' => 'type',
                    'type' => 'numeric',
                    'default' => null,
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Secret',
                    'key' => 'secret',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Expire',
                    'key' => 'expire',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'User Agent',
                    'key' => 'userAgent',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'IP',
                    'key' => 'ip',
                    'type' => 'ip',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
            ],
        ],
        self::SYSTEM_COLLECTION_MEMBERSHIPS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_MEMBERSHIPS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Membership',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Team ID',
                    'key' => 'teamId',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'User ID',
                    'key' => 'userId',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Roles',
                    'key' => 'roles',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => true,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Invited',
                    'key' => 'invited',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => false, //FIXME SHOULD BE REQUIRED
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Joined',
                    'key' => 'joined',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Confirm',
                    'key' => 'confirm',
                    'type' => 'boolean',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Secret',
                    'key' => 'secret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_TEAMS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_TEAMS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Team',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Date Created',
                    'key' => 'dateCreated',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,

                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Sum',
                    'key' => 'sum',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,

                ],
            ],
        ],
        self::SYSTEM_COLLECTION_PROJECTS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_PROJECTS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Project',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Description',
                    'key' => 'description',
                    'type' => 'text',
                    'default' => null,
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Logo',
                    'key' => 'logo',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'URL',
                    'key' => 'url',
                    'type' => 'url',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Team ID',
                    'key' => 'teamId',
                    'type' => 'text',
                    'default' => 0,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Clients',
                    'key' => 'clients',
                    'type' => 'url',
                    'default' => '',
                    'required' => false,
                    'array' => true,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Legal Name',
                    'key' => 'legalName',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Legal Country',
                    'key' => 'legalCountry',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Legal State',
                    'key' => 'legalState',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Legal City',
                    'key' => 'legalCity',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Legal Address',
                    'key' => 'legalAddress',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Legal Tax ID',
                    'key' => 'legalTaxId',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Callback',
                    'key' => 'usersOauthCallback', // TODO Deprecate this
                    'type' => 'url',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Bitbucket AppID',
                    'key' => 'usersOauthBitbucketAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Bitbucket Secret',
                    'key' => 'usersOauthBitbucketSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Facebook AppID',
                    'key' => 'usersOauthFacebookAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Facebook Secret',
                    'key' => 'usersOauthFacebookSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth GitHub AppID',
                    'key' => 'usersOauthGithubAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth GitHub Secret',
                    'key' => 'usersOauthGithubSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Gitlab AppID',
                    'key' => 'usersOauthGitlabAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Gitlab Secret',
                    'key' => 'usersOauthGitlabSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Google AppID',
                    'key' => 'usersOauthGoogleAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Google Secret',
                    'key' => 'usersOauthGoogleSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Instagram AppID',
                    'key' => 'usersOauthInstagramAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Instagram Secret',
                    'key' => 'usersOauthInstagramSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth LinkedIn AppID',
                    'key' => 'usersOauthLinkedinAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth LinkedIn Secret',
                    'key' => 'usersOauthLinkedinSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Microsoft AppID',
                    'key' => 'usersOauthMicrosoftAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Microsoft Secret',
                    'key' => 'usersOauthMicrosoftSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Twitter AppID',
                    'key' => 'usersOauthTwitterAppid',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'OAuth Twitter Secret',
                    'key' => 'usersOauthTwitterSecret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Webhooks',
                    'key' => 'webhooks',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_WEBHOOKS]
                    ],
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'API Keys',
                    'key' => 'keys',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_KEYS]
                    ],
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Tasks',
                    'key' => 'tasks',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_TASKS]
                    ],
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Platforms',
                    'key' => 'platforms',
                    'type' => 'documents',
                    'default' => [],
                    'required' => false,
                    'array' => true,
                    'options' => [
                        '$collection' => self::SYSTEM_COLLECTION_OPTIONS,
                        'whitelist' => [self::SYSTEM_COLLECTION_PLATFORMS]
                    ],
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_WEBHOOKS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_WEBHOOKS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Webhook',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Events',
                    'key' => 'events',
                    'type' => 'text',
                    'default' => null,
                    'required' => false,
                    'array' => true,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'URL',
                    'key' => 'url',
                    'type' => 'url',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Security',
                    'key' => 'security',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP User',
                    'key' => 'httpUser',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP Password',
                    'key' => 'httpPass',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_KEYS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_KEYS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Key',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Scopes',
                    'key' => 'scopes',
                    'type' => 'text',
                    'default' => null,
                    'required' => false,
                    'array' => true,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Secret',
                    'key' => 'secret',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_TASKS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_TASKS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Task',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Status',
                    'key' => 'status',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Updated',
                    'key' => 'updated',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Schedule',
                    'key' => 'schedule',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Previous',
                    'key' => 'previous',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Next',
                    'key' => 'next',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Duration',
                    'key' => 'duration',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Delay',
                    'key' => 'delay',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Security',
                    'key' => 'security',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP Method',
                    'key' => 'httpMethod',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP URL',
                    'key' => 'httpUrl',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP Headers',
                    'key' => 'httpHeaders',
                    'type' => 'text',
                    'default' => null,
                    'required' => false,
                    'array' => true,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP User',
                    'key' => 'httpUser',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'HTTP Password',
                    'key' => 'httpPass',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Log',
                    'key' => 'log',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Failures',
                    'key' => 'failures',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_PLATFORMS => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_PLATFORMS,
            '$permissions' => ['read' => ['*']],
            'name' => 'Platform',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Type',
                    'key' => 'type',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => null,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Created',
                    'key' => 'created',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Updated',
                    'key' => 'updated',
                    'type' => 'numeric',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Key',
                    'key' => 'key',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Store',
                    'key' => 'store',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Domains',
                    'key' => 'domains',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => true,
                ],
            ],
        ],
        self::SYSTEM_COLLECTION_FILES => [
            '$collection' => self::SYSTEM_COLLECTION_COLLECTIONS,
            '$uid' => self::SYSTEM_COLLECTION_FILES,
            '$permissions' => ['read' => ['*']],
            'name' => 'File',
            'structure' => true,
            'rules' => [
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Date Created',
                    'key' => 'dateCreated',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Folder ID',
                    'key' => 'folderId',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Name',
                    'key' => 'name',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Path',
                    'key' => 'path',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Signature',
                    'key' => 'signature',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Mime Type',
                    'key' => 'mimeType',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Size Original',
                    'key' => 'sizeOriginal',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Size Compressed',
                    'key' => 'sizeCompressed',
                    'type' => 'numeric',
                    'default' => 0,
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Algorithm',
                    'key' => 'algorithm',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Token',
                    'key' => 'token',
                    'type' => 'text',
                    'default' => '',
                    'required' => true,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'Comment',
                    'key' => 'comment',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'File OpenSSL Version',
                    'key' => 'fileOpenSSLVersion',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'File OpenSSL Cipher',
                    'key' => 'fileOpenSSLCipher',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'File OpenSSL Tag',
                    'key' => 'fileOpenSSLTag',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
                [
                    '$collection' => self::SYSTEM_COLLECTION_RULES,
                    'label' => 'File OpenSSL IV',
                    'key' => 'fileOpenSSLIV',
                    'type' => 'text',
                    'default' => '',
                    'required' => false,
                    'array' => false,
                ],
            ],
        ],
    ];

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Set Adapter
     *
     * @param Adapter $adapter
     * @return $this
     */
    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Set Namespace
     *
     * Set namespace to divide different scope of data sets
     *
     * @param $namespace
     * @return $this
     * @throws Exception
     */
    public function setNamespace($namespace)
    {
        $this->adapter->setNamespace($namespace);
        return $this;
    }

    /**
     * Get Namespace
     *
     * Get namespace of current set scope
     *
     * @return string
     * @throws Exception
     */
    public function getNamespace()
    {
        return $this->adapter->getNamespace();
    }

    /**
     * Create Namespace
     *
     * @param  int $namespace
     * @return bool
     */
    public function createNamespace($namespace)
    {
        return $this->adapter->createNamespace($namespace);
    }

    /**
     * @param array $options
     * @return Document[]|Document
     */
    public function getCollection(array $options)
    {
        $options = array_merge([
            'offset' => 0,
            'limit' => 15,
            'search' => '',
            'relations' => true,
            'orderField' => '$uid',
            'orderType' => 'ASC',
            'orderCast' => 'int',
            'first' => false,
            'last' => false,
            'filters' => [],
        ], $options);

        $results = $this->adapter->getCollection($options);

        foreach($results as &$node) {
            $node = new Document($node);
        }

        if($options['first']) {
            $results = reset($results);
        }

        if($options['last']) {
            $results = end($results);
        }

        return $results;
    }

    /**
     * @param int $id
     * @param bool $mock is mocked data allowed?
     * @return Document
     */
    public function getDocument($id, $mock = true)
    {
        if(is_null($id)) {
            return new Document([]);
        }

        $document   = new Document((isset($this->mocks[$id]) && $mock) ? $this->mocks[$id] : $this->adapter->getDocument($id));
        $validator  = new Authorization($document, 'read');

        if(!$validator->isValid($document->getPermissions())) { // Check if user has read access to this document
            return new Document([]);
        }

        return $document;
    }

    /**
     * @param array $data
     * @return Document|bool
     * @throws AuthorizationException
     * @throws StructureException
     */
    public function createDocument(array $data)
    {
        $document = new Document($data);

        $validator  = new Authorization($document, 'write');

        if(!$validator->isValid($document->getPermissions())) { // Check if user has write access to this document
            throw new AuthorizationException($validator->getDescription());
        }

        $validator = new Structure($this);

        if(!$validator->isValid($document)) {
            throw new StructureException($validator->getDescription()); // var_dump($validator->getDescription()); return false;
        }

        return new Document($this->adapter->createDocument($data));
    }

    /**
     * @param array $data
     * @return Document|false
     * @throws Exception
     */
    public function updateDocument(array $data)
    {
        if(!isset($data['$uid'])) {
            throw new Exception('Must define $uid attribute');
        }

        $document = $this->getDocument($data['$uid']); // TODO make sure user don\'t need read permission for write operations

        // Make sure reserved keys stay constant
        $data['$uid'] = $document->getUid();
        $data['$collection'] = $document->getCollection();

        $validator  = new Authorization($document, 'write');

        if(!$validator->isValid($document->getPermissions())) { // Check if user has write access to this document
            throw new AuthorizationException($validator->getDescription()); // var_dump($validator->getDescription()); return false;
        }

        $new = new Document($data);

        if(!$validator->isValid($new->getPermissions())) { // Check if user has write access to this document
            throw new AuthorizationException($validator->getDescription()); // var_dump($validator->getDescription()); return false;
        }

        $validator = new Structure($this);

        if(!$validator->isValid($new)) { // Make sure updated structure still apply collection rules (if any)
            throw new StructureException($validator->getDescription()); // var_dump($validator->getDescription()); return false;
        }

        return new Document($this->adapter->updateDocument($data));
    }

    /**
     * @param int $id
     * @return Document|false
     * @throws AuthorizationException
     */
    public function deleteDocument($id)
    {
        $document = $this->getDocument($id);

        $validator  = new Authorization($document, 'write');

        if(!$validator->isValid($document->getPermissions())) { // Check if user has write access to this document
            throw new AuthorizationException($validator->getDescription());
        }

        return new Document($this->adapter->deleteDocument($id));
    }

    /**
     * @return array
     */
    public function getDebug()
    {
        return $this->adapter->getDebug();
    }

    /**
     * @return int
     */
    public function getSum()
    {
        $debug = $this->getDebug();

        return (isset($debug['sum'])) ? $debug['sum'] : 0;
    }

    /**
     * @param array $options
     * @return int
     */
    public function getCount(array $options)
    {
        $options = array_merge([
            'filters' => [],
        ], $options);

        $results = $this->adapter->getCount($options);

        return $results;
    }

    /**
     * @return array
     */
    public  function getMocks() {
        return $this->mocks;
    }

    /**
     * Get Last Modified
     *
     * Return unix timestamp of last time a node queried in current session has been changed
     *
     * @return int
     */
    public function lastModified()
    {
        return $this->adapter->lastModified();
    }
}