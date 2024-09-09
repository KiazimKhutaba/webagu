<?php

namespace App\Modules\Quiz\Dtos;

use App\Modules\Quiz\Enums\PassedQuizStatus;

class QuizDto
{
    private array $quiz = [];
    private string $quiz_title = '';
    private array $questions = [];
    private array $tasks = [];
    private array $quizzes = [];
    private ?int $passed_quiz_id = null;
    private PassedQuizStatus $passed_quiz_status;
    private array $reply_files = [];
    private array $items = [];


    public function toArray(): array
    {
        $data = [
            'quiz' => [
                ...$this->quiz,
                'access_granted' => true
            ],
            'items' => [
                'questions' => $this->questions,
                'tasks' => $this->tasks,
                'quizzes' => $this->quizzes
            ],
            'passed_quiz' => [
                'id' => $this->passed_quiz_id,
                'status' => $this->passed_quiz_status
            ],
            'reply_files' => $this->reply_files
        ];

        if(count($this->items) > 0) {
            $data['items'] = $this->items;
        }

        if($this->quiz_title) {
            $data['quiz']['title'] = $this->quiz_title;
        }

        return $data;
    }

    public function getQuiz(): array
    {
        return $this->quiz;
    }

    public function setQuiz(array $quiz): self
    {
        $this->quiz = $quiz;
        return $this;
    }

    public function getQuizTitle(): string
    {
        return $this->quiz_title;
    }

    public function setQuizTitle(string $quiz_title): self
    {
        $this->quiz_title = $quiz_title;
        return $this;
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }

    public function setQuestions(array $questions): self
    {
        $this->questions = $questions;
        return $this;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function setTasks(array $tasks): self
    {
        $this->tasks = $tasks;
        return $this;
    }

    public function getQuizzes(): array
    {
        return $this->quizzes;
    }

    public function setQuizzes(array $quizzes): self
    {
        $this->quizzes = $quizzes;
        return $this;
    }

    public function getPassedQuizId(): ?int
    {
        return $this->passed_quiz_id;
    }

    public function setPassedQuizId(?int $passed_quiz_id): self
    {
        $this->passed_quiz_id = $passed_quiz_id;
        return $this;
    }

    public function getPassedQuizStatus(): PassedQuizStatus
    {
        return $this->passed_quiz_status;
    }

    public function setPassedQuizStatus(PassedQuizStatus $passed_quiz_status): QuizDto
    {
        $this->passed_quiz_status = $passed_quiz_status;
        return $this;
    }

    public function getReplyFiles(): array
    {
        return $this->reply_files;
    }

    public function setReplyFiles(array $reply_files): self
    {
        $this->reply_files = $reply_files;
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }
}
