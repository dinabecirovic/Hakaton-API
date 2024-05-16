<?php

namespace App\GitLab;

use GuzzleHttp\Client;

class RepositoryFiles
{
    /**
     * @var
     */
    private $client;

    /**
     * RepositoryFiles constructor.
     * @param $token
     */
    public function __construct( $token )
    {
        $gitlab_url = env("GITLAB_URL");
        $this->client = new Client(
            [
                'base_uri' => "$gitlab_url/api/graphql",
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

    }

    /**
     * @param $project_id
     * @param array $files
     * @param null $ref
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getFiles(string $fullpath, array $files, ?string $ref = null)
    {
        if (!$ref) {
            $ref = "master";
        }

        $paths = array_map(function ($file) {
            return (string) $file;
        }, $files);

        $query = '
            query GetFiles($fullpath: ID!, $files: [String!]!, $ref: String!) {
                project(fullPath: $fullpath) {
                    repository {
                        blobs(ref: $ref, paths: $files) {
                            nodes {
                                rawBlob
                            }
                        }
                    }
                }
            }
        ';

        $fileContents = [];

        $limit = 100;

        for($i = 0; $i <= count($paths) / $limit; $i++ ){

            $subArray = array_slice($paths, $i * $limit, $limit);

            $variables = [
                'fullpath' => $fullpath,
                'files' => $subArray,
                'ref' => $ref,
            ];

            $response = $this->client->post('', [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['data']['project']['repository']['blobs']['nodes'])) {
                foreach ($data['data']['project']['repository']['blobs']['nodes'] as $node) {
                    $fileContents[] = $node['rawBlob'];
                }
            }

        }

        return $fileContents;
    }

}
